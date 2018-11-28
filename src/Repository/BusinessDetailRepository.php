<?php

namespace App\Repository;

use App\Entity\BusinessDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BusinessDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessDetail[]    findAll()
 * @method BusinessDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessDetailRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BusinessDetail::class);
    }

//    /**
//     * @return BusinessDetail[] Returns an array of BusinessDetail objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BusinessDetail
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
