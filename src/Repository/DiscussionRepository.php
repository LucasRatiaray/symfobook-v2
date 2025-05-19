<?php

namespace App\Repository;

use App\Entity\Discussion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Discussion>
 */
class DiscussionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discussion::class);
    }

    //    /**
    //     * @return Discussion[] Returns an array of Discussion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Discussion
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.book', 'b')
            ->addSelect('b')
            ->leftJoin('b.author', 'a')  // Aussi charger l'auteur du livre
            ->addSelect('a')
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneWithAllRelations(int $id): ?Discussion
    {
        return $this->createQueryBuilder('d')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->leftJoin('d.book', 'b')
            ->addSelect('b')
            ->leftJoin('b.author', 'a')
            ->addSelect('a')
            ->leftJoin('d.comments', 'c')
            ->addSelect('c')
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
