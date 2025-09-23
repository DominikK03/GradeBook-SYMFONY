<?php

namespace App\Validator;

use App\Entity\Homework;
use App\Entity\Teacher;
use App\Exception\HomeworkAccessDeniedException;
use App\Exception\HomeworkValidationException;

final readonly class HomeworkValidator
{
    public function validateHomeworkData(array $homeworkData): void
    {
        if (empty($homeworkData['topic'])) {
            throw HomeworkValidationException::emptyTopic();
        }

        if (strlen($homeworkData['topic']) > 255) {
            throw HomeworkValidationException::topicTooLong();
        }

        if (isset($homeworkData['description']) && strlen($homeworkData['description']) > 255) {
            throw HomeworkValidationException::descriptionTooLong();
        }

        if (empty($homeworkData['class'])) {
            throw HomeworkValidationException::noGroupSelected();
        }

        if (isset($homeworkData['dueDate'])) {
            $dueDate = new \DateTime($homeworkData['dueDate']);
            $today = new \DateTime('today');
            
            if ($dueDate < $today) {
                throw HomeworkValidationException::invalidDueDate();
            }
        }
    }

    public function ensureTeacherCanAccessGroup(Teacher $teacher, int $groupId, array $assignedGroups): void
    {
        $hasAccess = false;
        
        foreach ($assignedGroups as $group) {
            if ($group->getId() === $groupId) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            throw HomeworkAccessDeniedException::groupNotAssigned();
        }
    }

    public function ensureTeacherOwnsHomework(Homework $homework, int $teacherId): void
    {
        if ($homework->getTeacher()->getId() !== $teacherId) {
            throw HomeworkAccessDeniedException::forTeacher();
        }
    }
}
