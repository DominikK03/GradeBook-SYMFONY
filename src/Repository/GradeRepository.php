<?php

namespace App\Repository;

use App\Entity\Grade;
use App\Enum\GradeType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Webmozart\Assert\Assert;

/**
 * @extends ServiceEntityRepository<Grade>
 */
class GradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    public function deleteGrade(Grade $grade): void
    {
        $this->getEntityManager()->remove($grade);
        $this->getEntityManager()->flush();
    }
}
