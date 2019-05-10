<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Reservation;
use App\Entity\User;
use App\Utils\GenerateContract;
use App\Utils\Util;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArenaParkController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $page = $entityManager->getRepository('App:Page')->findOneBy(['slug' => 'home']);
        $tarif = $entityManager->getRepository('App:Tarif')->findOneBy([]);
        $data = [];
        if ($sections = $page->getSections()->toArray()) {
            foreach ($sections as $section) {
                $data[$section->getSlug()] = $section;
            }
        }

        return $this->render(
            'home.html.twig',
            [
                'sections' => $data,
                'home' => true,
                'tarifs' => $tarif,
            ]
        );
    }

    /**
     * @Route("/services", name="service")
     */
    public function services(EntityManagerInterface $entityManager)
    {
        $page = $entityManager->getRepository('App:Page')->findOneBy(['slug' => 'services']);
        $services = $entityManager->getRepository('App:Service')->findAll();
        $allCar = [];
        $eachCar = [];
        $data = [];
        foreach ($services as $service) {
            $data['name'] = $service->getName();
            $prices = $service->getPrices();
            if (count($prices) == 3) {
                foreach ($prices as $price) {
                    if ($price['type'] == 1) {
                        $key = 'small';
                    } else {
                        if ($price['type'] == 2) {
                            $key = 'middle';
                        } else {
                            $key = 'big';
                        }
                    }
                    $data[$key] = $price['price'];
                }
                $eachCar[] = $data;
            } else {
                if (count($prices) == 1) {
                    $data['all'] = $prices[0]['price'];
                    $allCar[] = $data;
                }
            }
            $data = [];
        }

        if ($sections = $page->getSections()->toArray()) {
            foreach ($sections as $section) {
                $data[$section->getSlug()] = $section;
            }
        }


        return $this->render(
            'services.html.twig',
            [
                'sections' => $data,
                'allCar' => $allCar,
                'eachCar' => $eachCar,
                'services' => true,
            ]
        );
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq(EntityManagerInterface $entityManager)
    {
        $page = $entityManager->getRepository('App:Page')->findOneBy(['slug' => 'faq']);

        return $this->render(
            'faq.html.twig',
            [
                'sections' => $page->getSections()->toArray(),
                'faq' => true,
            ]
        );
    }

    /**
     * @Route("/tarifs", name="tarifs")
     */
    public function tarif(EntityManagerInterface $entityManager)
    {
        $page = $entityManager->getRepository('App:Page')->findOneBy(['slug' => 'tarif']);
        $tarif = $entityManager->getRepository('App:Tarif')->findOneBy([]);

        return $this->render(
            'tarif.html.twig',
            [
                'sections' => $page->getSections()->toArray(),
                'tarif' => true,
                'tarifs' => $tarif,
            ]
        );
    }

    /**
     * @Route("/conditions", name="conditions")
     */
    public function conditions(EntityManagerInterface $entityManager)
    {
        $page = $entityManager->getRepository('App:Page')->findOneBy(['slug' => 'generals_conditions']);

        return $this->render(
            'general-condition.html.twig',
            [
                'sections' => $page->getSections()->toArray(),
            ]
        );
    }

    /**
     * @Route("/protection", name="protection")
     */
    public function protection(EntityManagerInterface $entityManager)
    {
        $page = $entityManager->getRepository('App:Page')->findOneBy(['slug' => 'protection']);

        return $this->render(
            'general-condition.html.twig',
            [
                'sections' => $page->getSections()->toArray(),
            ]
        );
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(EntityManagerInterface $entityManager)
    {
        $page = $entityManager->getRepository('App:Page')->findOneBy(['slug' => 'contact']);
        $info = $entityManager->getRepository('App:Contact')->findOneBy([]);

        return $this->render(
            'contact.html.twig',
            [
                'sections' => $page->getSections()->toArray(),
                'contact' => $info,
            ]
        );
    }

    /**
     * @Route("/send-contact", name="send-contact")
     */
    public function sendContact(EntityManagerInterface $entityManager, \Swift_Mailer $mailer)
    {
        $data = Util::decodeBody();
        $info = $entityManager->getRepository('App:Contact')->findOneBy([]);

        $message = (new \Swift_Message('Contacto desde arena-park.ch'))
            ->setFrom('noreply@arena-park.ch')
            ->setTo($info->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/contact.html.twig',
                    array('data' => $data)
                ),
                'text/html'
            );

        return new JsonResponse([$mailer->send($message)]);
    }

    /**
     * @Route("/save-reservation", name="save-reservation")
     */
    public function saveReservation(
        Request $request,
        EntityManagerInterface $entityManager,
        GenerateContract $phpDoc,
        \Swift_Mailer $mailer
    ) {
        $userData = json_decode(stripslashes($request->get('user')), true);
        $user = (isset($userData['id']) && $userData['id']) ? $entityManager->getRepository('App:User')->find(
            $userData['id']
        ) : new User();
        $user->setName($userData['name']);
        $user->setEmail($userData['email']);
        $user->setPhone($userData['phone']);
        $user->setSex($userData['sex']);
        $entityManager->persist($user);

        $carData = json_decode(stripslashes($request->get('car')), true);
        $car = isset($userData['id']) ? $user->getCar() : new Car();
        $car->setUser($user);
        $car->setMark($carData['mark']);
        $car->setPlate($carData['plate']);
        $car->setColor($carData['color']);
        $entityManager->persist($car);

        $reservationData = json_decode(stripslashes($request->get('reservation')), true);
        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setCar($car);
        $reservation->setAirport($entityManager->getRepository('App:Airport')->find($reservationData['airport']));
        $reservation->setDateFlyIn(new \DateTime($reservationData['dateFlyIn'].' '.$reservationData['hourFlyIn']));
        $reservation->setDateCarIn(new \DateTime($reservationData['dateCarIn'].' '.$reservationData['hourCarIn']));
        $reservation->setDateFlyOut(new \DateTime($reservationData['dateFlyOut'].' '.$reservationData['hourFlyOut']));
        $reservation->setDateCarOut(new \DateTime($reservationData['dateCarOut'].' '.$reservationData['hourCarOut']));
        $reservation->setFly($reservationData['fly']);
        $reservation->setBaggage($reservationData['baggage'] ?? false);
        $tarif = $entityManager->getRepository('App:Tarif')->findOneBy([]);
        if ($tarif->getActiveDescount()) {
            $reservation->setDescount($tarif->getDescount());
        }
        $reservation->setPaymentType($reservationData['payment']);
        $reservation->setPayment($reservationData['total']);
        $reservation->setServices($reservationData['services'] ?? null);
        $reservation->setMessage($reservationData['message'] ?? null);

        if ($contract = $phpDoc->generateContract($entityManager, $user, $car, $reservation, $tarif)) {
            $entityManager->persist($contract);
            $reservation->setContract($contract);
            $entityManager->persist($reservation);
            $entityManager->flush();

            $paymentTypes = ['cash', 'visa', 'mastercard', 'postfinance_efinance', 'postfinance_card'];
            $psp_methods = array(
                'visa' => array('PM' => 'CreditCard', 'BRAND' => 'visa'),
                'mastercard' => array('PM' => 'CreditCard', 'BRAND' => 'eurocard'),
                'postfinance_efinance' => array('PM' => 'Postfinance e-finance', 'BRAND' => ''),
                'postfinance_card' => array('PM' => 'Postfinance card', 'BRAND' => ''),
            );

            // Payment method is a PSP method
            $key = $paymentTypes[$reservationData['payment'] - 1];
            if (array_key_exists($key, $psp_methods)) {

                $session = $this->get('session');
                $session->getFlashBag()->add('orderID', $reservation->getId());

                return $this->forward(
                    'App\Controller\PostFinanceController::getUrlRedirect',
                    [
                        'reservation' => $reservation,
                        'paymentData' => $psp_methods[$key]
                    ]
                );

            }

            $userName = $user->getSex()?'Monsieur ':'Madame ';
            $userName .= $user->getName();

            $message = (new \Swift_Message('Bienvenue chez Arena-Park'))
                ->setFrom('reservation@arena-park.ch')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/reservation.html.twig',
                        array('user' => $userName)
                    ),
                    'text/html'
                )->attach(\Swift_Attachment::fromPath($contract->getPath()));

            $mailer->send($message);

            $message = (new \Swift_Message("Reserva para:". $reservation->getDateCarIn()->format('d-m-Y H:i')))
                ->setFrom($user->getEmail())
                ->setTo('reservation@arena-park.ch')
                ->setBody(
                    $this->renderView(
                        'emails/reservation.html.twig',
                        array('user' => $userName)
                    ),
                    'text/html'
                )->attach(\Swift_Attachment::fromPath($contract->getPath()));

            return new JsonResponse([$mailer->send($message)]);
        }

        return new JsonResponse(['error']);
    }


    /**
     * @Route("/reservation-park", name="reservation")
     */
    public function reservation(Request $request)
    {
        $params = ['reservation' => true];

        if($error = $request->get('errorPayment'))
            $params['errorPayment'] = $error;

        if($request->get('reservationConfirmed'))
            $params['reservationConfirmed'] = true;

         return $this->render(
            'reservation.html.twig', $params);
    }
}
