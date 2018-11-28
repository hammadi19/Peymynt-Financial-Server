<?php

namespace App\Repository;

use App\Entity\EstimateProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EstimateProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstimateProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstimateProduct[]    findAll()
 * @method EstimateProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimateProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EstimateProduct::class);
    }

//    /**
//     * @return EstimateProduct[] Returns an array of EstimateProduct objects
//     */
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
    public function findOneBySomeField($value): ?EstimateProduct
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
