<?php

namespace App\Repository;

use App\Entity\AppUserLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AppUserLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppUserLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppUserLink[]    findAll()
 * @method AppUserLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppUserLinkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AppUserLink::class);
    }

//    /**
//     * @return AppUserLink[] Returns an array of AppUserLink objects
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
    public function findOneBySomeField($value): ?AppUserLink
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
