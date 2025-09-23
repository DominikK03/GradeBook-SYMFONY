<?php

namespace App\Validator;

use App\Entity\Grade;
use App\Exception\GradeAccessDeniedException;
use App\Exception\GradeNotFoundException;

final readonly class GradeValidator
{

    public function validateGradeData(array $gradeData): array
    {
        $errors = [];

        if (!isset($gradeData['value']) || !is_numeric($gradeData['value'])) {
            $errors[] = 'Grade value is required and must be a number.';
        } elseif ($gradeData['value'] < 1 || $gradeData['value'] > 6) {
            $errors[] = 'Grade must be between 1-6.';
        }

        if (empty($gradeData['type'])) {
            $errors[] = 'Grade type is required.';
        }

        if (!isset($gradeData['wage']) || !is_numeric($gradeData['wage'])) {
            $errors[] = 'Grade weight is required and must be a number.';
        } elseif ($gradeData['wage'] < 1 || $gradeData['wage'] > 3) {
            $errors[] = 'Grade weight must be between 1-3.';
        }

        if (empty($gradeData['student_id'])) {
            $errors[] = 'Student ID is required.';
        }

        return $errors;
    }


    /**
     * @throws GradeNotFoundException
     */
    public function ensureGradeExists(?Grade $grade, int $gradeId): void
    {
        if (!$grade){
            throw GradeNotFoundException::withId($gradeId);
        }
    }
    public function ensureTeacherOwnsGrade(Grade $grade, int $teacherId): void
    {
        if ($grade->getTeacher()->getId() !== $teacherId) {
            throw GradeAccessDeniedException::forTeacher();
        }
    }


}
