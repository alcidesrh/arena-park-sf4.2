<?php
/**
 * Created by PhpStorm.
 * User: alcides
 * Date: 4/10/2018
 * Time: 7:44 p.m.
 */

namespace App\Utils;


use App\Entity\Car;
use App\Entity\Contract;
use App\Entity\Reservation;
use App\Entity\Tarif;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpWord\TemplateProcessor;

class GenerateContract
{

    const PATH = 'contracts';
    const CONTRACT_TEMPLATE_DESCOUNT = 'contracts/contract-descount.docx';
    const CONTRACT_TEMPLATE = 'contracts/contract.docx';
    const CONTRACT_TEMPLATE_ONLINE = 'contracts/contract-inline-paid.docx';

    public function generateContract(
        EntityManagerInterface $entityManager,
        User $user = null,
        Car $car = null,
        Reservation $reservation = null,
        Tarif $tarif
    ) {

        if ($tarif->getActiveDescount()) {
            $document = new TemplateProcessor(self::CONTRACT_TEMPLATE_DESCOUNT);
        } else {
            $document = new TemplateProcessor(self::CONTRACT_TEMPLATE);
        }

//        if(!$onlinePaid)
//            $document = $this->loadTemplate( 'doc/contract.docx' );
//        else{
//            $document = $this->loadTemplate( 'doc/contract-inline-paid.docx' );
//            $document->setValue( 'totalreal', $onlinePaid );
//        }

//        if (!isset($_SESSION['enterprise']))
//            $document = $this->loadTemplate('doc/contract.docx');
//        else
//            $document = $this->loadTemplate('doc/contract-enterprise.docx');
        $number = count($entityManager->getRepository('App:Reservation')->findAll());


        $document->setValue('reservation', $number);
        $document->setValue('date', date('d/m/Y'));
        $document->setValue('date_actual', date('d/m/Y'));
        $document->setValue('dateM', date('d/m/Y'));

        $document->setValue('pCharge', number_format($tarif->getPriceCharge(), 2));
        $document->setValue('annulation', number_format($tarif->getAnnulation(), 2));

        $document->setValue('conductor', utf8_decode($user->getName()));
        $document->setValue('movil', $user->getPhone());
        $document->setValue('email', utf8_decode($user->getEmail()));

        $document->setValue('date_fly_in', $reservation->getDateFlyIn()->format('d-m-Y H:i'));
        $document->setValue('date_fly_out', $reservation->getDateFlyOut()->format('d-m-Y H:i'));
        $document->setValue('date_car_in', $reservation->getDateCarIn()->format('d-m-Y H:i'));
        $document->setValue('date_car_out', $reservation->getDateCarOut()->format('d-m-Y H:i'));
        $document->setValue('destination_back', utf8_decode($reservation->getAirport()->getName()));
        $document->setValue('fly_number_back', utf8_decode($reservation->getFly()));
        $document->setValue( 'baggage', $reservation->getBaggage()?'Oui':'Nom' );

        // mathieu@ollea.ch
        if ($reservation->getPaymentType() === 1) {
            $document->setValue('paymode', utf8_decode("Cash, sur place le jour du départ"));
            $document->setValue('pay_details', "");
            $document->setValue('tva', '');
            $document->setValue('tva2', '');
        } else {
            $document->setValue(
                'paymode',
                utf8_decode("Payé en ligne par ").ucwords($reservation->getPaymentType() == 2 ? 'Visa' : 'MasterCard')
            );
            if ($tarif->getTva()) {
                $document->setValue('tva', 'TVA');
                $document->setValue('tva2', '+'.$tarif->getTva().'%');
            }
        }

        if ($reservation->getMessage()) {

            $document->setValue('remarques', "Remarques:");
            $document->setValue('remarquesV', utf8_decode($reservation->getMessage()));
        } else {
            $document->setValue('remarques', '');
            $document->setValue('remarquesV', '');
        }

        $cont = 1;
        $subtotal = $tarif->getAnnulation() + $tarif->getPriceCharge();
        if ($services = $reservation->getServices()) {
            foreach ($services as $value) {

                $service = $entityManager->getRepository('App:Service')->find($value['id']);

                $document->setValue('s'.$cont, 1);

                $document->setValue('chf'.$cont, 'CHF');

                $document->setValue('service'.$cont, $service->getName());

                $document->setValue(
                    'servicec'.$cont++,
                    number_format($service->getPrices()[$value['price']]['price'], 2)
                );
                $subtotal += $service->getPrices()[$value['price']]['price'];

//                $chargeService += $value[ 'charge' ];
            }
        }

        for ($i = 1; $i < 7; $i++) {

            $document->setValue('chf'.$i, '');

            $document->setValue('s'.$i, ' ');

            $document->setValue('service'.$i, ' ');

            $document->setValue('servicec'.$i, ' ');
        }

        $dStart = $reservation->getDateCarIn();
        $dEnd = $reservation->getDateCarOut();
        $dDiff = $dStart->diff($dEnd);


        $document->setValue('gar', $dDiff->days + 1);

        $charge = ($dDiff->days + 1) * $tarif->getDay();
        $subtotal += $charge;
        $document->setValue('charge', $charge, number_format($charge, 2));

        $total = $reservation->getPayment();// + $percent;
        if ($tarif->getActiveDescount()) {
            $descount = $tarif->getDescount();
            $document->setValue('total', number_format($subtotal, 2));
            $document->setValue('percent', "-$descount%");
            $document->setValue('totalp', number_format($total, 2));
        } else {
            $document->setValue('totalp', number_format($total, 2));
        }


        $contractPath = self::PATH.'/contracts/user'.$user->getId().'date'.$reservation->getCreateAt()->format(
                'Ymdhms'
            );


        $document->setValue('dateF', date('d/m/Y'));

        $document->setValue('mark', utf8_decode($car->getMark()));
        $document->setValue('plate', utf8_decode($car->getPlate()));
        $document->setValue('color', utf8_decode($car->getColor()));


        if (mkdir($contractPath)) {

            chmod($contractPath, 0777);

            $contractPath .= '/contract.docx';

            $contract = new Contract();
            $reservation->setContract($contract);
            $document->saveAs($contractPath);
            $contract->setPath($contractPath);
            $entityManager->persist($contract);

            return $contract;

        }

        return false;
    }
}