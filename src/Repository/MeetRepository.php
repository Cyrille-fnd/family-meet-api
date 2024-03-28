<?php

namespace App\Repository;

use App\Entity\Meet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meet>
 *
 * @method Meet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meet[]    findAll()
 * @method Meet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetRepository extends ServiceEntityRepository
{
    private int $nbResultsPerPage;

    public function __construct(
        ManagerRegistry $registry,
        int $nbResultsPerPage
    ) {
        $this->nbResultsPerPage = $nbResultsPerPage;
        parent::__construct($registry, Meet::class);
    }

    //    /**
    //     * @return Meet[] Returns an array of Meet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Meet
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return Meet[]
     */
    public function findByPage(int $page): array
    {
        $offset = ($page * $this->nbResultsPerPage) - $this->nbResultsPerPage;

        return $this->createQueryBuilder('meet')
            ->setMaxResults($this->nbResultsPerPage)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }
}
