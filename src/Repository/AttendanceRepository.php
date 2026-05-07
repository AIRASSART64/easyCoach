<?php

namespace App\Repository;

use App\Entity\Attendance;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Attendance>
 */
class AttendanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attendance::class);
    }

    public function countInjuredPlayers(User $user): int
{
   
    return (int) $this->createQueryBuilder('a')
        ->select('count(DISTINCT p.id)')
        ->join('a.player', 'p')
        ->join('a.status', 's')
        ->andWhere('p.coach = :coach')
        ->andWhere('s.name = :statusName')
        ->setParameter('coach', $user)
        ->setParameter('statusName', 'Blessé')
        ->getQuery()
        ->getSingleScalarResult();
}

    public function getGlobalAttendanceRate(User $user): float
{
    $qb = $this->createQueryBuilder('a')
        ->join('a.player', 'p')
        ->join('a.status', 's')
        ->andWhere('p.coach = :coach')
        ->setParameter('coach', $user);

    $allAttendances = (clone $qb)->select('count(a.id)')->getQuery()->getSingleScalarResult();
    
    if ($allAttendances == 0) return 0.0;

    $presents = $qb->select('count(a.id)')
        ->andWhere('s.name = :status')
        ->setParameter('status', 'Présent')
        ->getQuery()
        ->getSingleScalarResult();

    return round(($presents / $allAttendances) * 100, 1);
}
    public function findAllByCoach(User $user): array
{
    return $this->createQueryBuilder('a')
        ->join('a.player', 'p') 
        ->andWhere('p.coach = :coach') 
        ->setParameter('coach', $user)
        ->orderBy('a.startDate', 'DESC') 
        ->getQuery()
        ->getResult();
}



    //    /**
    //     * @return Attendance[] Returns an array of Attendance objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Attendance
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
