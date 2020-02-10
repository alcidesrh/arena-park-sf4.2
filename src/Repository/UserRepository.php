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


    
    public function getUsersByIds($param, $exclude = false)
    {
        $query = $this->createQueryBuilder('u')->select('u.email, u.id, u.name');

        $where = 'u.unsubscribe != 1';
        $ids = $param['ids'];

        if( $count = count($ids) ){

         $where .= ' AND (';

            
         for($i = 0; $i < $count; $i++){
            if($i == 0){
             if(!$exclude)
              $where .= "u.id = ".$ids[$i];
             else $where .= "u.id != ".$ids[$i];
            }
            else{
             if(!$exclude)   
              $where .= " OR u.id = ".$ids[$i];
             else 
             $where .= " AND u.id != ".$ids[$i]; 
            }
         }

         $where .= ')';

         $query->where($where);
        }
                    
        if(isset($param['page'])){
           $from = ($param['page'] * $param['perPage']) - $param['perPage'];
           $query->setFirstResult($from)->setMaxResults($from + $param['perPage']);
        }
        return $query
            ->getQuery()
            ->getResult()
        ;
    }
    
}
