<?php

namespace App\Service;

use App\Entity\Student;
use App\Repository\ScheduleRepository;
use App\Repository\StudentRepository;

final readonly class StudentService
{

    public function __construct(private StudentRepository  $studentRepository,
                                private ScheduleRepository $scheduleRepository,
                                private GradeService $gradeService
    )
    {
    }

    public function getStudentById(int $studentId): ?Student
    {
        return $this->studentRepository->find($studentId);
    }

    public function getStudentSubjects(Student $student): array
    {
        $group = $student->getGroup();
        if (!$group) {
            return [];
        }
        return $this->scheduleRepository->findSubjectsByGroupID($group->getId());
    }

    public function getStudentWithGradesById(Student $student): ?array
    {
        return $this->gradeService->getStudentGradesGrouped($this->studentRepository->findStudentWithGrades($student));
    }

}
