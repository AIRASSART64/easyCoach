<?php

namespace App\Repository;

use App\Entity\Attendance;
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

   public function countInjuredPlayers(): int
{
    $lastMonth = new \DateTime('-1 month');
    
    $qb = $this->createQueryBuilder('a')
        ->select('count(DISTINCT p.id)')
        ->join('a.player', 'p')
        ->join('a.status', 's')
        ->leftJoin('a.training', 't') 
        ->where('s.name LIKE :status')
        ->andWhere('t.date >= :date') 
        ->setParameter('status', '%Blessé%')
        ->setParameter('date', $lastMonth);

    return (int) $qb->getQuery()->getSingleScalarResult();
}

    public function getGlobalAttendanceRate(): int
    {
    $qb = $this->createQueryBuilder('a')
        ->select('s.name, count(a.id) as total')
        ->join('a.status', 's')
        ->groupBy('s.name')
        ->getQuery()
        ->getResult();

    $present = 0;
    $total = 0;

    foreach ($qb as $row) {
        $total += $row['total'];
        if (strtolower($row['name']) === 'présent') {
            $present = $row['total'];
        }
    }

    if ($total === 0) return 0;

    return round(($present / $total) * 100);
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
