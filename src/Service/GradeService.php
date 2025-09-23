<?php

namespace App\Service;

use App\Entity\Grade;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Enum\GradeType;
use App\Event\NewGradeAdded;
use App\Exception\GradeNotFoundException;
use App\Exception\ZeroTeacherSpecializationsException;
use App\Repository\GradeRepository;
use App\Repository\StudentRepository;
use App\Validator\GradeValidator;
use App\Validator\TeacherValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class GradeService
{
    public function __construct(private EntityManagerInterface   $em,
                                private GradeRepository          $gradeRepository,
                                private GradeValidator           $gradeValidator,
                                private StudentRepository        $studentRepository,
                                private EventDispatcherInterface $eventDispatcher,
                                private TeacherValidator $teacherValidator)
    {
    }

    /**
     * @throws ZeroTeacherSpecializationsException
     */
    public function createGrade(array $gradeData, Student $student, Teacher $teacher): Grade
    {
        $this->teacherValidator->validateTeacher($teacher);
        $specializations = $teacher->getSpecialization();
        $grade = new Grade();
        $grade->setValue((int)$gradeData['value']);
        $grade->setType(GradeType::from($gradeData['type']));
        $grade->setWage((int)$gradeData['wage']);
        $grade->setStudent($student);
        $grade->setTeacher($teacher);
        $grade->setSubject($specializations[0]);
        $grade->setDate(new DateTime());
        $grade->setDescription($gradeData['description']);

        return $grade;


    }

    public function saveGrade(Grade $grade): void
    {
        $this->em->persist($grade);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new NewGradeAdded($grade));
    }

    public function getGradeById(int $id): ?Grade
    {
        return $this->gradeRepository->find($id);
    }

    /**
     * @throws GradeNotFoundException
     */
    public function updateGrade(int $gradeId, array $gradeData, int $teacherId): ?Grade
    {
        $grade = $this->getGradeById($gradeId);

        $this->gradeValidator->ensureGradeExists($grade, $gradeId);
        $this->gradeValidator->ensureTeacherOwnsGrade($grade, $teacherId);

        $grade->setValue((int) $gradeData['value']);
        $grade->setType(GradeType::from($gradeData['type']));
        $grade->setWage((int) $gradeData['wage']);
        $grade->setDescription($gradeData['description']);

        return $grade;
    }

    /**
     * @throws GradeNotFoundException
     */
    public function deleteGrade(int $gradeId, int $teacherId): void
    {
        $grade = $this->getGradeById($gradeId);
        $this->gradeValidator->ensureGradeExists($grade, $gradeId);
        $this->gradeValidator->ensureTeacherOwnsGrade($grade, $teacherId);
        $this->gradeRepository->deleteGrade($grade);
    }

    public function calculateWeightedAverage(array $grades): ?float
    {
        if (empty($grades)) {
            return null;
        }

        $weightedSum = 0;
        $wagesSum = 0;
        foreach ($grades as $grade) {
            $weightedSum += $grade->getValue() * $grade->getWage();
            $wagesSum += $grade->getWage();
        }
        return $wagesSum ? $weightedSum / $wagesSum : 0.0;
    }

    public function prepareStudentsForTeacherView(array $studentsWithGrades): array
    {
        $result = [];
        foreach ($studentsWithGrades as $student) {
            $studentData['student'] = $student;
            $studentData['grades'] = [];
            $grades = $student->getGrades()->toArray();
            $studentData['weightedAverage'] = $this->calculateWeightedAverage($grades);
            foreach ($grades as $grade) {
                $gradeType = $grade->getType()->value;
                $studentData['grades'][$gradeType][] = $grade;
            }
            $result[] = $studentData;
        }
        return $result;
    }

    public function getStudentsWithGradesForTeacher(string $groupName, Teacher $teacher): array
    {
        return $this->prepareStudentsForTeacherView($this->studentRepository->findStudentsWithGradesByGroupAndSubject($groupName, $teacher));
    }

    public function getStudentGradesGrouped(Student $student): array
    {
        $result = [];
        $grades = $student->getGrades()->toArray();
        foreach ($grades as $grade) {
            $gradeType = $grade->getType()->value;
            $gradeSubject = $grade->getSubject()->value;
            $result[$gradeSubject][$gradeType][] = $grade;
        }
        foreach ($result as $subject => $gradesByType){
            $allGradesForSubject = [];
            foreach ($gradesByType as $gradesArray){
                $allGradesForSubject = array_merge($allGradesForSubject, $gradesArray);
            }
            $result[$subject]['weightedAverage'] = $this->calculateWeightedAverage($allGradesForSubject);
        }
        return $result;
    }
}
