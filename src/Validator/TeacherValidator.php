<?php

namespace App\Validator;

use App\Entity\Teacher;
use App\Exception\ZeroTeacherSpecializationsException;

class TeacherValidator
{
    /**
     * @throws ZeroTeacherSpecializationsException
     */
    public function validateTeacher(Teacher $teacher): void
    {
        $specializations = $teacher->getSpecialization();
        if (empty($specializations)) {
            throw new ZeroTeacherSpecializationsException();
        }
    }
}