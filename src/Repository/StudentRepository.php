<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Enum\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }
    public function findStudentsWithGradesByGroupAndSubject(string $groupName, Teacher $teacher): array
    {        
        return $this->createQueryBuilder('s')
            ->addSelect('g')
            ->addSelect('p')
            ->addSelect('gr')
            ->innerJoin("s.person", "p")
            ->innerJoin("s.class", "gr")
            ->leftJoin("s.grades", "g", "WITH", 'g.subject = :teacherSpecialization')
            ->where('gr.name = :groupName')
            ->setParameter('teacherSpecialization', $teacher->getSpecialization()[0])
            ->setParameter('groupName', $groupName)
            ->orderBy("p.lastName", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findStudentWithGrades(Student $student) : ?Student
    {
        return $this->createQueryBuilder('s')
            ->addSelect('g')
            ->leftJoin('s.grades', 'g')
            ->where('s.id = :id')
            ->setParameter('id', $student)
            ->getQuery()
            ->getOneOrNullResult();
    }

}

