<?php

namespace App\Repository;

use App\Entity\Todolist;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Todolist>
 */
class TodolistRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Todolist::class);
  }

  /**
   * @return Todolist[] Returns an array of Todolist objects
   */
  public function findByUser(User $user)
  {
    return $this->createQueryBuilder('t')
      ->andWhere('t.user = :user')
      ->setParameter('user', $user)
      ->orderBy('t.id', 'ASC')
      ->getQuery()
      ->getResult();
  }

  //    /**
  //     * @return Todolist[] Returns an array of Todolist objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('t')
  //            ->andWhere('t.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('t.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Todolist
  //    {
  //        return $this->createQueryBuilder('t')
  //            ->andWhere('t.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
