<?php
/**
 * Created by PhpStorm.
 * User: alcides
 * Date: 1/22/2019
 * Time: 10:09 a.m.
 */

namespace App\Command;


use App\Entity\Car;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MigrateCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'arena-park:migrate-db';

    private $em;

    private $emOldDb;


    public function __construct(ContainerInterface $container)
    {
        $this->em = $container->get('doctrine')->getManager();
        $this->emOldDb = $container->get('doctrine')->getManager('customer');


        parent::__construct();
    }

    protected function configure()
    {
        // the short description shown while running "php bin/console list"
        $this->setDescription('Migrate old arena-park database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->deleteOldReservation();
        return;
        //Insert Users
//        $this->insertUsers();
//Insert Reservation
        $this->insertReservation();


//        $query = $entityManager2->createNativeQuery('SELECT DISTINCT u.name, u.last_name, u.email, u.phone_movil, u.email, c.mark, c.plate, c.color, r.day_date_car_in, r.hour_car_in FROM user u INNER JOIN car c ON u.userid = c.userid INNER JOIN reservation r ON u.userid = r.userid AND c.idcar = r.idcar', $rsm);


        $output->writeln('Done');
    }

    public function insertUsers()
    {
        $rsm = new ResultSetMapping();
        //Users fields
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('last_name', 'last_name');
        $rsm->addScalarResult('phone_movil', 'phone');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('sex', 'sex');

        //Get Users
        $query = $this->emOldDb->createNativeQuery(
            'SELECT DISTINCT u.name, u.last_name, u.email, u.phone_movil, u.email, u.sex FROM user u',
            $rsm
        );

        $data = $query->getResult();

        $emails = [];
        foreach ($data as $value) {
            if (!in_array($value['email'], $emails)) {
                $newUser = new User();
                $newUser->setName($value['name'].' '.$value['last_name'])
                    ->setEmail($value['email'])
                    ->setPhone($value['phone'])
                    ->setSex($value['sex'] == 'Mr');
                $this->em->persist($newUser);
                $emails[] = $value['email'];
            }
        }
        $this->em->flush();
    }

    public function insertReservation()
    {
        $rsm = new ResultSetMapping();
        //Reservation fields
        $rsm->addScalarResult('day_date_car_in', 'dateCarIn');
        $rsm->addScalarResult('hour_car_in', 'hour_car_in');
        $rsm->addScalarResult('day_date_car_out', 'dateCarOut');
        $rsm->addScalarResult('hour_car_out', 'hour_car_out');
        $rsm->addScalarResult('destination_fly_back', 'destination_fly_back');
        $rsm->addScalarResult('fly_number_back', 'fly_number_back');
        $rsm->addScalarResult('pay_mode', 'paymentType');
        $rsm->addScalarResult('charge', 'payment');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('baggage', 'baggage');
        $rsm->addScalarResult('remarques', 'message');
        $rsm->addScalarResult('idcar', 'car');
        $rsm->addScalarResult('userid', 'user');

        //Get Reservations
        $query = $this->emOldDb->createNativeQuery(
            'SELECT DISTINCT * FROM reservation r INNER JOIN user u WHERE r.userid = u.userid',
            $rsm
        );

        $data = $query->getResult();

        $car_user = [];
        foreach ($data as $value) {

            try {
                if ($value['dateCarIn'] == 2) {
                    $break = 1;
                }
                $dateCarIn = new \DateTime($value['dateCarIn']);
                list($hour, $minute) = explode(':',$value['hour_car_in']);
                $dateCarIn->setTime($hour, $minute);
                $dateCarOut = new \DateTime($value['dateCarOut']);
                list($hour, $minute) = explode(':', $value['hour_car_out']);
                $dateCarOut->setTime($hour, $minute);
            } catch (\Throwable $e) {
                continue;
            }

            if (!($airport = $this->em->getRepository('App:Airport')->findOneBy(
                ['name' => $value['destination_fly_back']]
            ))) {
                continue;
            }
            $userOld = $this->getUserOld($value['user']);
            if(!($user = $this->getUser($userOld['email']))){
                $user = new User();
                $user->setName($userOld['name'].' '.$userOld['last_name'])
                    ->setEmail($userOld['email'])
                    ->setPhone($userOld['phone'])
                    ->setSex($userOld['sex'] == 'Mr');
                $this->em->persist($user);
                $this->em->flush();
            }
            if (array_key_exists($user->getEmail(), $car_user)) {
                $newCar = $car_user[$user->getEmail()];
            } else {
                $newCar = new Car();
                $newCar->setUser($user);
                $car_user[$user->getEmail()] = $newCar;
            }
            $car = $this->getCar($value['car']);
            $newCar->setMark($car[0]['mark'])->setPlate($car[0]['plate'])->setColor($car[0]['color']);
            $this->em->persist($newCar);
            $reservation = new Reservation();
            $reservation->setUser($user)
                ->setCar($newCar)
                ->setDateCarIn($dateCarIn)
                ->setDateCarOut($dateCarOut)
                ->setPayment($value['payment'])
                ->setFly($value['fly_number_back'])
                ->setAirport($airport)
                ->setPaymentType(
                    is_null(
                        $value['paymentType']
                    ) || $value['paymentType'] == 'cash' ? 1 : $value['paymentType'] == 'visa' ? 2 : 3
                )
                ->setBaggage($value['baggage'])
                ->setMessage($value['message']);
            $this->em->persist($reservation);
        }
        $this->em->flush();
    }

    public function getCar($id)
    {
        $rsm = new ResultSetMapping();
        //        //Car fields
        $rsm->addScalarResult('mark', 'mark');
        $rsm->addScalarResult('plate', 'plate');
        $rsm->addScalarResult('color', 'color');

        $query = $this->emOldDb->createNativeQuery(
            'SELECT DISTINCT * FROM car c WHERE c.idcar = ?',
            $rsm
        )->setParameter(1, $id);

        $data = $query->getResult();

        return $data;
    }

    public function getUser($email)
    {
        $data = $this->em->getRepository('App:User')->findOneBy(['email' => $email]);

        return $data;
    }

    public function getUserOld($id)
    {
        $rsm = new ResultSetMapping();
        //Users fields
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('last_name', 'last_name');
        $rsm->addScalarResult('phone_movil', 'phone');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('sex', 'sex');

        $query = $this->emOldDb->createNativeQuery(
            'SELECT DISTINCT * FROM user u WHERE u.userid = ?',
            $rsm
        )->setParameter(1, $id);

        $data = $query->getResult();

        return $data[0];
    }

    public function deleteOldReservation(){
        $now = new \DateTime();
        $now->setTime(0,0);
        $reservations = $this->em->getRepository('App:Reservation')->findAll();
        foreach ($reservations as $reservation){
            if($reservation->getDateCarIn() < $now)
                $this->em->remove();
        }
        $this->em->flush();
    }
}