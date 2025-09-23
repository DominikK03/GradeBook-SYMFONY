<?php

namespace App\Factory;

use App\DTO\HomeworkDto;
use App\Entity\Group;
use App\Entity\Homework;
use App\Entity\Teacher;
use App\Enum\HomeworkStatus;
use DateTime;
use DateMalformedStringException;

final readonly class HomeworkFactory
{
    /**
     * @throws DateMalformedStringException
     */
    public function createFromDto(HomeworkDto $dto, Teacher $teacher, Group $group): Homework
    {
        $specializations = $teacher->getSpecialization();
        
        $homework = new Homework();
        $homework->setTopic($dto->topic);
        $homework->setDescription($dto->description);
        $homework->setDueDate(new DateTime($dto->dueDate));
        $homework->setTeacher($teacher);
        $homework->setGroup($group);
        $homework->setSubject($specializations[0]);
        $homework->setStatus(HomeworkStatus::ACTIVE);

        return $homework;
    }

    /**
     * @throws DateMalformedStringException
     */
    public function updateFromDto(Homework $homework, HomeworkDto $dto, ?Group $newGroup = null): Homework
    {
        $homework->setTopic($dto->topic);
        $homework->setDescription($dto->description);
        $homework->setDueDate(new DateTime($dto->dueDate));
        
        if ($newGroup !== null) {
            $homework->setGroup($newGroup);
        }

        return $homework;
    }
}