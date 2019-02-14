<?php

namespace App\Repository;

use App\Entity\ComparationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComparationService|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComparationService|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComparationService[]    findAll()
 * @method ComparationService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComparationServiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComparationService::class);
    }

    // /**
    //  * @return ComparationService[] Returns an array of ComparationService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComparationService
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
