<?php

namespace App\Repository;

use App\Entity\AugmentationContrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AugmentationContrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method AugmentationContrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method AugmentationContrat[]    findAll()
 * @method AugmentationContrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AugmentationContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AugmentationContrat::class);
    }

    // /**
    //  * @return AugmentationContrat[] Returns an array of AugmentationContrat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AugmentationContrat
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
