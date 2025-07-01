<?php

namespace App\Repository;

use App\Entity\Temperature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Temperature>
 */
class TemperatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Temperature::class);
    }

    public function findLast(): ?Temperature
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTodayTemperatures(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.temperature', 'TO_CHAR(t.createdAt, \'YYYY-MM-DD HH24:MI:SS\') as created_at')
            ->where('t.createdAt >= :start_of_day')
            ->setParameter('start_of_day', new \DateTimeImmutable('today'))
            ->orderBy('t.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLast30DaysDailyAverages(): array
    {
        return $this->createQueryBuilder('t')
            ->select('TO_CHAR(t.createdAt, \'YYYY-MM-DD\') as day', 'AVG(t.temperature) as avg_temp')
            ->where('t.createdAt >= :start_date')
            ->setParameter('start_date', new \DateTimeImmutable('-30 days'))
            ->groupBy('day')
            ->orderBy('day')
            ->getQuery()
            ->getResult();
    }
}