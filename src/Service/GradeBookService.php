<?php

namespace App\Service;

use App\Entity\Group;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Form\GroupSelectorFormType;
use App\Repository\GroupRepository;
use App\Repository\ScheduleRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

final readonly class GradeBookService
{
    public function __construct(private FormFactoryInterface $formFactory,
                                private GroupRepository      $groupRepository,
                                private ScheduleRepository $scheduleRepository
                                )
    {
    }


    public function getGroupsAssignedToTeacher(int $teacherID): array
    {
        $groupsFromSchedule = $this->scheduleRepository->findGroupsAssignedToTeacher($teacherID);
        $groupAsGroupTeacher = $this->groupRepository->findGroupsByGroupTeacher($teacherID);
        
        $groups = [];
        if ($groupsFromSchedule) {
            foreach ($groupsFromSchedule as $schedule) {
                if (is_array($schedule) && isset($schedule[0])) {
                    $group = $schedule[1];
                } else {
                    $group = $schedule->getClass();
                }
                
                $found = false;
                foreach ($groups as $existingGroup) {
                    if ($existingGroup->getId() === $group->getId()) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $groups[] = $group;
                }
            }
        }
        
        if ($groupAsGroupTeacher) {
            $groups[] = $groupAsGroupTeacher;
        }
        
        return $groups;
    }

    public function generateGroupSelectionForm(int $teacherID): FormInterface
    {
        return $this->formFactory->create(GroupSelectorFormType::class, null, ['teacherID' => $teacherID]);
    }

}
