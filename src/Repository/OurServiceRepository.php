<?php

namespace App\Repository;

use App\Entity\OurService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OurService|null find($id, $lockMode = null, $lockVersion = null)
 * @method OurService|null findOneBy(array $criteria, array $orderBy = null)
 * @method OurService[]    findAll()
 * @method OurService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OurServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OurService::class);
    }

    // /**
    //  * @return OurService[] Returns an array of OurService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OurService
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
