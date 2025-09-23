<?php

namespace App\Service;

use App\DataFixtures\TeacherFixtures;
use App\Entity\Teacher;

final class TeacherService
{
    public function __construct(
        private readonly UserDataService $userDataService
    ) {
    }
    public static array $usedSpecializations = [];
    public static function getRandomTeacherSpecializationForFixture(): string
    {
        $specializations = array_column(TeacherFixtures::TEACHERS, 'specialization');
        $availableSpecializations = array_filter($specializations, function($spec) {
            return !in_array(strtolower($spec->value), self::$usedSpecializations);
        });

        if (empty($availableSpecializations)) {
            self::$usedSpecializations = [];
            return self::getRandomTeacherSpecializationForFixture();
        }

        shuffle($availableSpecializations);
        $selected = array_pop($availableSpecializations);
        self::$usedSpecializations[] = strtolower($selected->value);

        return strtolower($selected->value);
    }

    public function ensureTeacher(): Teacher
    {
        $user = $this->userDataService->getCurrentUser();
        $teacher = $user?->getTeacher();
        
        if (!$teacher) {
            throw new \RuntimeException('User does not have a teacher role');
        }
        
        return $teacher;
    }
}
