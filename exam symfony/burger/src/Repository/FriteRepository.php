<?php

namespace App\Repository;

use App\Entity\Frite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Frite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Frite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Frite[]    findAll()
 * @method Frite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Frite::class);
    }

    // /**
    //  * @return Frite[] Returns an array of Frite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Frite
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
