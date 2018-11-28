<?php

namespace App\Repository;

use App\Entity\AppAdminUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AppAdminUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppAdminUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppAdminUser[]    findAll()
 * @method AppAdminUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppAdminUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AppAdminUser::class);
    }

//    /**
//     * @return AppAdminUser[] Returns an array of AppAdminUser objects
//     */
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
    public function findOneBySomeField($value): ?AppAdminUser
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
