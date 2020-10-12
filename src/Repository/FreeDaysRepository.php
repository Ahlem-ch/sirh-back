<?php

namespace App\Repository;

use App\Entity\FreeDays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FreeDays|null find($id, $lockMode = null, $lockVersion = null)
 * @method FreeDays|null findOneBy(array $criteria, array $orderBy = null)
 * @method FreeDays[]    findAll()
 * @method FreeDays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FreeDaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FreeDays::class);
    }

    // /**
    //  * @return FreeDays[] Returns an array of FreeDays objects
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
    public function findOneBySomeField($value): ?FreeDays
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
