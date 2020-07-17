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
        Tarif $tarif,
        $sms = false,
        $discountType = false
    ) {

        if ($discount = $reservation->getDescount()) {
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

        $number = count($entityManager->getRepository('App:Reservation')->findAll()) + 1;
        $document->setValue('reservation', '00'.$number);
        $document->setValue('date', $reservation->getCreateAt()->format('d-m-Y'));

        $document->setValue('pCharge', number_format($tarif->getPriceCharge(), 2));
        $document->setValue('annulation', number_format($tarif->getAnnulation(), 2));

        $sex = $user->getSex()?'Monsieur: ':'Madame: ';
        $document->setValue('conductor', $sex.' '.utf8_decode($user->getName()));
        $document->setValue('movil', $user->getPhone());
        $document->setValue('email', utf8_decode($user->getEmail()));

        $document->setValue('date_fly_in', $reservation->getDateFlyIn()->format('d-m-Y H:i'));
        $document->setValue('date_fly_out', $reservation->getDateFlyOut()->format('d-m-Y H:i'));
        $document->setValue('date_car_in', $reservation->getDateCarIn()->format('d-m-Y H:i'));
        $document->setValue('date_car_out', $reservation->getDateCarOut()->format('d-m-Y H:i'));
        $document->setValue('date_car_in2', $reservation->getDateCarIn()->format('d-m-Y'));
        $document->setValue('date_car_out2', $reservation->getDateCarOut()->format('d-m-Y'));
        $document->setValue('destination_back', utf8_decode($reservation->getAirport()->getName()));
        $document->setValue('fly_number_back', utf8_decode($reservation->getFly()));
        $document->setValue('baggage', $reservation->getBaggage() ? 'Oui' : 'Non');

        // mathieu@ollea.ch
        if ($reservation->getPaymentType() === 1) {
            $document->setValue('paymode', utf8_decode("Cash, sur place le jour du départ"));
            $document->setValue('pay_details', "");
            $document->setValue('tva', '');
            $document->setValue('tva2', '');
        } else {
            if($reservation->getPaymentType() == 2){
                $paymode = 'Visa';
            }
            else if($reservation->getPaymentType() == 3){
                $paymode = 'MasterCard';
            }
            else if($reservation->getPaymentType() == 4){
                $paymode = 'PostFinance Card';
            }
            else{
                $paymode = 'PostFinance e-finance';
            }
            $document->setValue(
                'paymode',
                utf8_decode("Payé en ligne par ").ucwords($paymode)
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
        if($sms){
            $document->setValue('chf0', 'CHF');
            $document->setValue('servicec0', $tarif->getSmsConfirmation());
            $document->setValue('service0', 'SMS de confirmation');
            $cont = 1;
            $i = 1;
        }
        else{
            $cont = 0;
            $i = 0;
        }
        
        $subtotal = $tarif->getAnnulation() + $tarif->getPriceCharge();
        $aux = false;//check control visual
        $parkingCouvert = false;//check Parking couvert
        if ($services = $reservation->getServices()) {
            foreach ($services as $value) {

                if($value['id'] == 7)$aux = true;
                $service = $entityManager->getRepository('App:Service')->find($value['id']);

                if($service->getDescription() == 'parking'){                  
                 $parkingCouvert = true;
                 $document->setValue('parking', "Rue des Coopératives 27 - CP 1217, Meyrin. ( Parking des Arbères ).");
                 $document->setValue('service'.$cont, 'Parking Couvert');
                }
                else {
                    
                $document->setValue('service'.$cont, $service->getName());
                }

                $document->setValue('s'.$cont, 1);

                $document->setValue('chf'.$cont, 'CHF');

                $document->setValue(
                    'servicec'.$cont++,
                    number_format($service->getPrices()[$value['price']]['price'], 2)
                );
                $subtotal += $service->getPrices()[$value['price']]['price'];

//                $chargeService += $value[ 'charge' ];
            }
        }
        if(!$parkingCouvert) $document->setValue('parking', "Chemin de l'Avanchet 26 - CP 1216 - Cointrin Genève");                  

        // $document->setValue('parking', "Chemin de l'Avanchet 26 - CP 1216 - Cointrin Genève");  
          
        for (; $i < 8; $i++) {

            if(!$aux){
                if(!$sms && $i == 0){
                 $document->setValue('chf'.$i, 'CHF');

                 $document->setValue('s'.$i, 1);

                 $document->setValue('service'.$i, 'Voulez-vous faire le contrôle');

                 $document->setValue('servicec'.$i, '0');
                }
                else if($sms && $i == 1){
                 $document->setValue('chf'.$i, 'CHF');

                 $document->setValue('s'.$i, 1);

                 $document->setValue('service'.$i, 'Voulez-vous faire le contrôle');

                 $document->setValue('servicec'.$i, '0');
                }
                else{
                    $document->setValue('chf'.$i, '');
    
                    $document->setValue('s'.$i, ' ');
    
                    $document->setValue('service'.$i, ' ');
    
                    $document->setValue('servicec'.$i, ' ');
                }
                
            }
            else{
                $document->setValue('chf'.$i, '');

                $document->setValue('s'.$i, ' ');

                $document->setValue('service'.$i, ' ');

                $document->setValue('servicec'.$i, ' ');
            }

        }

        $dStart = clone $reservation->getDateCarIn();
        $dEnd = clone $reservation->getDateCarOut();
        $dStart->setTime(0, 0, 0);
        $dEnd->setTime(23, 59, 59);
        $dDiff = $dStart->diff($dEnd);


        $document->setValue('gar', $dDiff->days + 1);

        $charge = ($dDiff->days + 1) * $tarif->getDay();
        $subtotal += $charge;
        $document->setValue('charge', number_format($charge, 2));

        $total = $reservation->getPayment();// + $percent;

        if ($discount) {
            $document->setValue('chf', 'CHF');
            $document->setValue('subtotal', 'SOUS-TOTAL');
            $document->setValue('total', number_format($subtotal, 2));
            $document->setValue('percent', "-$discount%");
            $document->setValue('totalp', number_format($total, 2));
            if($discountType){
                $reservations = $entityManager->getRepository('App:Reservation')->getLastYear($user->getId(), $user->getDateDiscount()); 
                $document->setValue('discountText', "Félicitations!! Plus de $reservations réservations au cours des 12 mois" );
            }
            else
               $document->setValue('discountText', '');
        } else {
            $document->setValue('discountText', '');
            if($reservation->getPaymentType() === 1){
                $document->setValue('chf', '');
                $document->setValue('subtotal', '');
                $document->setValue('total', '');
            }
            else{
                $document->setValue('chf', 'CHF');
                $document->setValue('subtotal', 'SOUS-TOTAL');
                $document->setValue('total', number_format($subtotal, 2));
            }
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