<?php

namespace App\Repository;

use App\Entity\TypeExp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TypeExp|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeExp|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeExp[]    findAll()
 * @method TypeExp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeExpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeExp::class);
    }

    // /**
    //  * @return TypeExp[] Returns an array of TypeExp objects
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
    public function findOneBySomeField($value): ?TypeExp
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
