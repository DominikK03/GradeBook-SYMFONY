<?php

namespace App\Service;

use App\DTO\HomeworkDto;
use App\Entity\Homework;
use App\Validator\FormValidator;
use Exception;

class HomeworkOperationService
{
    public function __construct(
        private FormValidator $formValidator,
        private TeacherService $teacherService,
        private GradeBookService $gradeBookService,
        private HomeworkService $homeworkService
    ) {
    }

    public function processHomeworkCreation(array $homeworkData): array
    {
        if (!$homeworkData) {
            return ['success' => false, 'error' => 'no_data'];
        }

        unset($homeworkData['_token']);

        $homeworkDto = HomeworkDto::fromArray($homeworkData);
        $errors = $this->formValidator->validateDto($homeworkDto);

        if (!empty($errors)) {
            return ['success' => false, 'error' => 'validation', 'message' => implode(' ', $errors)];
        }

        $teacher = $this->teacherService->ensureTeacher();
        $assignedGroups = $this->gradeBookService->getGroupsAssignedToTeacher($teacher->getId());

        try {
            $homework = $this->homeworkService->createHomework($homeworkDto, $teacher, $assignedGroups);
            $this->homeworkService->saveHomework($homework);

            return ['success' => true, 'homework' => $homework];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'exception', 'message' => $e->getMessage()];
        }
    }
}