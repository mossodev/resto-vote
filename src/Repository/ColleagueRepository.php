<?php

namespace App\Repository;

use App\Entity\Colleague;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Colleague|null find($id, $lockMode = null, $lockVersion = null)
 * @method Colleague|null findOneBy(array $criteria, array $orderBy = null)
 * @method Colleague[]    findAll()
 * @method Colleague[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColleagueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Colleague::class);
    }

//    /**
//     * @return Colleague[] Returns an array of Colleague objects
//     */
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
    public function findOneBySomeField($value): ?Colleague
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
