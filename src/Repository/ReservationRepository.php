<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

     /**
      * @return Reservation[] Returns an array of Reservation objects
      */

    public function findByDay($date, $date2)
    {
        return [
            'carIn' => $this->createQueryBuilder('r')
                ->andWhere('r.dateCarIn >= :date AND r.dateCarIn <= :date2')
                ->setParameters(['date' => $date, 'date2' => $date2])
                ->getQuery()
                ->getResult(),
            'carOut' => $this->createQueryBuilder('r')
                ->andWhere('r.dateCarOut >= :date AND r.dateCarOut <= :date2')
                ->setParameters(['date' => $date, 'date2' => $date2])
                ->getQuery()
                ->getResult()
        ]
        ;
    }


    
    public function getLastYear($user_id, $date)
    {
        
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
   ->where('r.user = ?1')->andWhere('r.createAt > ?2')
   ->setParameter(1, $user_id)->setParameter(2, $date)
        ->getQuery()
        ->getSingleScalarResult();         
    }
    
}
