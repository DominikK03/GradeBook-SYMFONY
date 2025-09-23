<?php

namespace App\Repository;

use App\Entity\Homework;
use App\Enum\HomeworkStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Homework>
 */
class HomeworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Homework::class);
    }

    public function findActiveHomeworkByGroupId(int $groupId): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.class = :groupId')
            ->andWhere('h.status = :status')
            ->setParameter('groupId', $groupId)
            ->setParameter('status', HomeworkStatus::ACTIVE)
            ->orderBy('h.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
