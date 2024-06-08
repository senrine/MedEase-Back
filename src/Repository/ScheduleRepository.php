<?php

namespace App\Repository;

use App\Entity\Schedule;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @extends ServiceEntityRepository<Schedule>
 */
class ScheduleRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Schedule::class);
        $this->entityManager = $entityManager;
    }

    public function save(Schedule $schedule): void
    {
        $this->entityManager->persist($schedule);
        $this->entityManager->flush();
    }

    public function remove(Schedule $schedule): void
    {
        $this->entityManager->remove($schedule);
        $this->entityManager->flush();
    }

    public function findOneById(int $id): Schedule
    {
        return $this->createQueryBuilder("s")
            ->where("s.id = :id")
            ->setParameter("id", $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function existingSchedule(User $professional, \DateTime $day, \DateTime $startTime, \DateTime $endTime): bool
    {
        $qb = $this->createQueryBuilder('s');
        $qb->where("s.professional = :professional")
            ->andWhere("s.day = :day")
            ->andWhere($qb->expr()->orX(
                $qb->expr()->andX(
                    "s.startTime <= :startTime",
                    "s.endTime > :startTime"
                ),
                $qb->expr()->andX(
                    "s.startTime < :endTime",
                    "s.endTime >= :endTime"
                ),
                $qb->expr()->andX(
                    "s.startTime >= :startTime",
                    "s.endTime <= :endTime"
                )
            ))
            ->setParameter("professional", $professional)
            ->setParameter("startTime", $startTime)
            ->setParameter("endTime", $endTime)
            ->setParameter("day", $day);

        $result = $qb->getQuery()->getOneOrNullResult();

        dump($result);

        return $result !== null;

    }

    public function getSchedules(User $professionalId, \DateTime $startDateMinusOne, \DateTime $endDatePlusOne)
    {
        return $this->createQueryBuilder('s')
            ->where('s.professional = :professionalId')
            ->andWhere('s.day BETWEEN :startDateMinusOne AND :endDatePlusOne')
            ->setParameter('professionalId', $professionalId)
            ->setParameter('startDateMinusOne', $startDateMinusOne)
            ->setParameter('endDatePlusOne', $endDatePlusOne)
            ->orderBy('s.day', 'ASC')
            ->addOrderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult();

    }

    //    /**
    //     * @return Schedule[] Returns an array of Schedule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Schedule
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}