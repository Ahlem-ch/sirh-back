<?php

namespace App\Repository;

use App\Entity\ConfigAutorisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConfigAutorisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigAutorisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigAutorisation[]    findAll()
 * @method ConfigAutorisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigAutorisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigAutorisation::class);
    }

    // /**
    //  * @return ConfigAutorisation[] Returns an array of ConfigAutorisation objects
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
    public function findOneBySomeField($value): ?ConfigAutorisation
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
