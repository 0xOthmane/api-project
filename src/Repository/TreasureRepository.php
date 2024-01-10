<?php

namespace App\Repository;

use App\Entity\Treasure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Treasure>
 *
 * @method Treasure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Treasure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Treasure[]    findAll()
 * @method Treasure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TreasureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Treasure::class);
    }

//    /**
//     * @return Treasure[] Returns an array of Treasure objects
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

//    public function findOneBySomeField($value): ?Treasure
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
