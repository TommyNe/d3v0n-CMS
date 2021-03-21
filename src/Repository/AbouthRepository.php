<?php

namespace App\Repository;

use App\Entity\Abouth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Abouth|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abouth|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abouth[]    findAll()
 * @method Abouth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbouthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abouth::class);
    }

    // /**
    //  * @return Abouth[] Returns an array of Abouth objects
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
    public function findOneBySomeField($value): ?Abouth
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
