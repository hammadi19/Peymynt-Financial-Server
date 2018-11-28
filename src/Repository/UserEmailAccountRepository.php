<?php

namespace App\Repository;

use App\Entity\UserEmailAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserEmailAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserEmailAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserEmailAccount[]    findAll()
 * @method UserEmailAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserEmailAccountRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserEmailAccount::class);
    }

//    /**
//     * @return UserEmailAccount[] Returns an array of UserEmailAccount objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserEmailAccount
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
