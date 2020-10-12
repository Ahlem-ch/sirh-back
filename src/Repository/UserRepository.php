<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function countCelibataire()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n.etat_civil)')
                ->andWhere('n.etat_civil = :searchTerm')
                ->setParameter('searchTerm', 'celibataire')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function countMarie()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n.etat_civil)')
                ->andWhere('n.etat_civil = :searchTerm')
                ->setParameter('searchTerm', 'marie')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function countFemme()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n.sexe)')
                ->andWhere('n.sexe = :searchTerm')
                ->setParameter('searchTerm', 'Femme')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function countHomme()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n.sexe)')
                ->andWhere('n.sexe = :searchTerm')
                ->setParameter('searchTerm', 'Homme')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function listDatesNaissance()
    {
        return $this->createQueryBuilder('n')
            ->select('n.date_naissance')
            ->getQuery()
            ->getResult();
    }

    public function listDepartement($value)
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n.departement)')
                ->leftJoin('n.departement', 'dep')
                ->andWhere('n.departement = dep.id')
                ->andWhere(' dep.libelle_departement = :searchTerm')
                ->setParameter('searchTerm', $value)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function listPostes($value)
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n.poste)')
                ->leftJoin('n.poste', 'p')
                ->andWhere('n.poste = p.id')
                ->andWhere(' p.libelle_poste = :searchTerm')
                ->setParameter('searchTerm', $value)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function listeTechnologies($value)
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n)')
                ->leftJoin('n.technologies', 'p')
                ->andWhere('p.libelle = :searchTerm')
                ->setParameter('searchTerm', $value)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function countEmployes()
    {
        try {
            return $this->createQueryBuilder('n')
                ->select('COUNT(n)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
        }
    }



}
