<?php

namespace App\Repository;

use App\Entity\Conge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Conge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conge[]    findAll()
 * @method Conge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conge::class);
    }


    public function selectDureeConge()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('n.duree')
                ->andWhere('n.statut = :searchTerm')
                ->setParameter('searchTerm', 'Validé')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }
    public function countDureeConge($min, $max , $val)
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n)')
                ->andWhere('n.statut = :searchTerm')
                ->setParameter('searchTerm', 'Validé')
                ->andWhere('n.num_mois=:searchTerm2')
                ->setParameter('searchTerm2', $val)
                ->andWhere('n.duree >= :min')
                ->andWhere('n.duree <=  :max')
                ->setParameter('min', $min)
                ->setParameter('max', $max)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        };
    }

    public function selectMoisDateConge()
    {
        return $this->createQueryBuilder('n')
            ->select('n.date_debut')
            ->andWhere('n.statut = :searchTerm')
            ->setParameter('searchTerm', 'Validé')
            ->getQuery()
            ->getResult();
    }

}
