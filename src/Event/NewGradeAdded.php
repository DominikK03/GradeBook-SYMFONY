<?php

namespace App\Event;

use App\Entity\Grade;
use App\Entity\Student;
use App\Entity\StudentParent;

class NewGradeAdded
{
    public function __construct(private readonly Grade $grade)
    {
    }

    /**
     * @return Grade
     */
    public function getGrade(): Grade
    {
        return $this->grade;
    }

    public function getStudent(): Student
    {
        return $this->grade->getStudent();
    }
    public function getParent(): ?StudentParent
    {
        return $this->getStudent()->getStudentParent();
    }

}
