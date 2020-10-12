<?php

namespace App\Repository;

use App\Entity\Contrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Contrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrat[]    findAll()
 * @method Contrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrat::class);
    }


    public function sumSalaireTunisie()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('SUM(n.actuel_salaire)')
                ->leftJoin('n.user' , 'user' )
                ->andWhere('user.localisation = :searchTerm')
                ->setParameter('searchTerm', 'Tunisie')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function sumSalaireFrance()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('SUM(n.actuel_salaire)')
                ->leftJoin('n.user' , 'user' )
                ->andWhere('user.localisation = :searchTerm')
                ->setParameter('searchTerm', 'France')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function avgSalaireTunise()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('AVG(n.actuel_salaire)')
                ->leftJoin('n.user' , 'user' )
                ->andWhere('user.localisation = :searchTerm')
                ->setParameter('searchTerm', 'Tunisie')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function avgSalaireFrance()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('AVG(n.actuel_salaire)')
                ->leftJoin('n.user' , 'user' )
                ->andWhere('user.localisation = :searchTerm')
                ->setParameter('searchTerm', 'France')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function countContratType($value)
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n.type)')
                ->andWhere('n.type = :searchTerm')
                ->setParameter('searchTerm', $value)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }
}
