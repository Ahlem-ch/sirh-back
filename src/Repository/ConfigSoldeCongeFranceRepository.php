<?php

namespace App\Repository;

use App\Entity\ConfigSoldeCongeFrance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConfigSoldeCongeFrance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigSoldeCongeFrance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigSoldeCongeFrance[]    findAll()
 * @method ConfigSoldeCongeFrance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigSoldeCongeFranceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigSoldeCongeFrance::class);
    }

    // /**
    //  * @return ConfigSoldeCongeFrance[] Returns an array of ConfigSoldeCongeFrance objects
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
    public function findOneBySomeField($value): ?ConfigSoldeCongeFrance
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
