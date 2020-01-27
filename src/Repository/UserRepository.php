<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */

    public function getByEmail($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email LIKE :val')
            ->setParameter('val', "%$value%")
            ->getQuery()
            ->getResult()
        ;
    }


    
    public function getUsersByIds($ids, $exclude = false)
    {
        $where = '';
        $count = count($ids);
        for($i = 0; $i < $count; $i++){
            if($i == 0){
             if(!$exclude)
              $where = "u.id = ".$ids[$i];
             else $where = "u.id != ".$ids[$i];
            }
            else{
             if(!$exclude)   
              $where .= " OR u.id = ".$ids[$i];
             else 
             $where .= " AND u.id != ".$ids[$i]; 
            }
        }
        return $this->createQueryBuilder('u')->select('u.email')
            ->where($where)
            ->getQuery()
            ->getResult()
        ;
    }
    
}
