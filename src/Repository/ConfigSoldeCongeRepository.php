<?php

namespace App\Repository;

use App\Entity\ConfigSoldeConge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConfigSoldeConge|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigSoldeConge|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigSoldeConge[]    findAll()
 * @method ConfigSoldeConge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigSoldeCongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigSoldeConge::class);
    }

    // /**
    //  * @return ConfigSoldeConge[] Returns an array of ConfigSoldeConge objects
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
    public function findOneBySomeField($value): ?ConfigSoldeConge
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
