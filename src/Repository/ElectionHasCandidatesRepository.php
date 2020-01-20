<?php

namespace App\Repository;

use App\Entity\ElectionHasCandidates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ElectionHasCandidates|null find($id, $lockMode = null, $lockVersion = null)
 * @method ElectionHasCandidates|null findOneBy(array $criteria, array $orderBy = null)
 * @method ElectionHasCandidates[]    findAll()
 * @method ElectionHasCandidates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElectionHasCandidatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElectionHasCandidates::class);
    }

    // /**
    //  * @return ElectionHasCandidates[] Returns an array of ElectionHasCandidates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ElectionHasCandidates
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
