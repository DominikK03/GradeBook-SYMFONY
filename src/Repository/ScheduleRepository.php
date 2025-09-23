<?php

namespace App\Repository;

use App\Entity\Schedule;
use App\Entity\Student;
use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Schedule>
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Schedule::class);
    }
    public function findSubjectsByGroupID(int $groupID): array
    {
        $results = $this->createQueryBuilder('s')
            ->select("DISTINCT s.subject")
            ->where('s.class = :groupID')
            ->setParameter('groupID', $groupID)
            ->getQuery()
            ->getResult();
        return array_map(fn($result) => $result['subject']->value, $results);
    }
    public function findGroupsAssignedToTeacher(int $teacherID): ?array
    {
        return $this->createQueryBuilder("s")
            ->innerJoin("s.class", "c")
            ->addSelect("c")
            ->andWhere("s.teacher = :teacherID")
            ->setParameter("teacherID", $teacherID)
            ->getQuery()
            ->getResult();
    }

    public function findByTeacher(Teacher $teacher): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.class', 'c')
            ->addSelect('c')
            ->where('s.teacher = :teacher')
            ->setParameter('teacher', $teacher)
            ->orderBy('s.weekday', 'ASC')
            ->addOrderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByStudent(Student $student): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.class', 'c')
            ->leftJoin('s.teacher', 't')
            ->leftJoin('t.person', 'tp')
            ->addSelect('c', 't', 'tp')
            ->where('s.class = :class')
            ->setParameter('class', $student->getGroup())
            ->orderBy('s.weekday', 'ASC')
            ->addOrderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
