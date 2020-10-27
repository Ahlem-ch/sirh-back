<?php

namespace App\Repository;

use App\Entity\Teletravail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Teletravail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teletravail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teletravail[]    findAll()
 * @method Teletravail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeletravailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teletravail::class);
    }

    // /**
    //  * @return Teletravail[] Returns an array of Teletravail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Teletravail
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
