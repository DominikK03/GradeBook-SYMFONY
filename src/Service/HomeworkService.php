<?php

namespace App\Service;

use App\DTO\HomeworkDto;
use App\Entity\Homework;
use App\Entity\Teacher;
use App\Enum\HomeworkStatus;
use App\Event\NewHomeworkAdded;
use App\Factory\HomeworkFactory;
use App\Repository\GroupRepository;
use App\Repository\HomeworkRepository;
use App\Validator\HomeworkValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;

final readonly class HomeworkService
{
    public function __construct(
        private EntityManagerInterface $em,
        private HomeworkRepository $homeworkRepository,
        private HomeworkValidator $homeworkValidator,
        private GroupRepository $groupRepository,
        private EventDispatcherInterface $eventDispatcher,
        private HomeworkFactory $homeworkFactory
    ) {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function createHomework(HomeworkDto $homeworkDto, Teacher $teacher, array $assignedGroups): Homework
    {
        $group = $this->groupRepository->find($homeworkDto->classId);
        if (!$group) {
            throw new InvalidArgumentException('Group does not exist');
        }

        $this->homeworkValidator->ensureTeacherCanAccessGroup($teacher, $group->getId(), $assignedGroups);

        return $this->homeworkFactory->createFromDto($homeworkDto, $teacher, $group);
    }

    public function saveHomework(Homework $homework): void
    {
        $this->em->persist($homework);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new NewHomeworkAdded($homework));
    }

    public function getHomeworkById(int $id): ?Homework
    {
        return $this->homeworkRepository->find($id);
    }

    public function getActiveHomeworkForTeacher(Teacher $teacher): array
    {
        return $this->homeworkRepository->findBy([
            'teacher' => $teacher,
            'status' => HomeworkStatus::ACTIVE
        ]);
    }

    public function getHomeworkForGroup(int $groupId): array
    {
        return $this->homeworkRepository->findActiveHomeworkByGroupId($groupId);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function updateHomework(Homework $homework, HomeworkDto $homeworkDto, Teacher $teacher, array $assignedGroups): Homework
    {
        $this->homeworkValidator->ensureTeacherOwnsHomework($homework, $teacher->getId());

        $newGroup = null;
        if ((int)$homeworkDto->classId !== $homework->getGroup()->getId()) {
            $group = $this->groupRepository->find($homeworkDto->classId);
            if (!$group) {
                throw new InvalidArgumentException('Group does not exist');
            }
            $this->homeworkValidator->ensureTeacherCanAccessGroup($teacher, $group->getId(), $assignedGroups);
            $newGroup = $group;
        }

        return $this->homeworkFactory->updateFromDto($homework, $homeworkDto, $newGroup);
    }

    public function deleteHomework(int $homeworkId, Teacher $teacher): void
    {
        $homework = $this->getHomeworkById($homeworkId);
        
        if (!$homework) {
            throw new InvalidArgumentException('Homework does not exist');
        }

        $this->homeworkValidator->ensureTeacherOwnsHomework($homework, $teacher->getId());
        
        $this->em->remove($homework);
        $this->em->flush();
    }

    public function changeHomeworkStatus(int $homeworkId, HomeworkStatus $newStatus, Teacher $teacher): Homework
    {
        $homework = $this->getHomeworkById($homeworkId);
        
        if (!$homework) {
            throw new InvalidArgumentException('Zadanie domowe nie istnieje');
        }

        $this->homeworkValidator->ensureTeacherOwnsHomework($homework, $teacher->getId());
        
        $homework->setStatus($newStatus);
        $this->em->flush();
        
        return $homework;
    }
}
