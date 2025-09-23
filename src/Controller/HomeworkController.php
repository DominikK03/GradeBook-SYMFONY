<?php

namespace App\Controller;

use App\Enum\HomeworkStatus;
use App\Response\HomeworkResponse\HomeworkErrorResponse;
use App\Response\HomeworkResponse\HomeworkNotFoundResponse;
use App\Response\HomeworkResponse\HomeworkStatusChangeResponse;
use App\Response\HomeworkResponse\HomeworkSuccessResponse;
use App\Response\HomeworkResponse\InvalidFormDataResponse;
use App\Response\HomeworkResponse\MissingStatusResponse;
use App\Service\GradeBookService;
use App\Service\HomeworkOperationService;
use App\Service\HomeworkService;
use App\Service\TeacherService;
use App\Service\UserDataService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeworkController extends AbstractController
{
    public function __construct(
        private readonly HomeworkService $homeworkService,
        private readonly UserDataService $userDataService,
        private readonly HomeworkOperationService $homeworkOperationService,
        private readonly GradeBookService $gradeBookService,
        private readonly TeacherService $teacherService
    ) {
    }

    #[Route('/homework', name: 'app_homework')]
    #[IsGranted('ROLE_TEACHER')]
    public function teacherHomework(): Response
    {
        $teacher = $this->teacherService->ensureTeacher();
        $homework = $this->homeworkService->getActiveHomeworkForTeacher($teacher);
        $groups = $this->gradeBookService->getGroupsAssignedToTeacher($teacher->getId());

        return $this->render('homework/teacher.html.twig', [
            'TopNavbarTitle' => 'navigation.homework',
            'userData' => $this->userDataService->getPerson(),
            'homework' => $homework,
            'groups' => $groups,
            'subjects' => $teacher->getSpecialization()
        ]);
    }

    #[Route('/student/homework', name: 'app_homework_student')]
    #[IsGranted('ROLE_STUDENT')]
    public function studentHomework(): Response
    {
        $student = $this->userDataService->getCurrentUser()->getRoleEntity();
        $group = $student->getGroup();
        
        if (!$group) {
            return $this->render('homework/student.html.twig', [
                'TopNavbarTitle' => 'navigation.homework',
                'userData' => $this->userDataService->getPerson(),
                'homework' => [],
                'message' => 'You are not assigned to any group'
            ]);
        }

        $homework = $this->homeworkService->getHomeworkForGroup($group->getId());

        return $this->render('homework/student.html.twig', [
            'TopNavbarTitle' => 'navigation.homework', 
            'userData' => $this->userDataService->getPerson(),
            'homework' => $homework
        ]);
    }

    #[Route('/homework/add', name: 'app_homework_add', methods: ['POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function addHomework(Request $request): JsonResponse
    {
        $homeworkData = $request->request->all();

        $result = $this->homeworkOperationService->processHomeworkCreation($homeworkData);

        if (!$result['success']) {
            return match ($result['error']) {
                'no_data' => new InvalidFormDataResponse(),
                'validation' => new HomeworkErrorResponse($result['message']),
                'exception' => new HomeworkErrorResponse($result['message']),
                default => new HomeworkErrorResponse('Unknown error occurred')
            };
        }

        return new HomeworkSuccessResponse('Homework has been added successfully.');
    }

    #[Route('/homework/edit/{id}', name: 'app_homework_edit', methods: ['POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function editHomework(int $id, Request $request): JsonResponse
    {
        $homeworkData = $request->request->all();
        
        if (!$homeworkData) {
            return new InvalidFormDataResponse();
        }

        unset($homeworkData['_token']);

        $homeworkDto = HomeworkDto::fromArray($homeworkData);
        $errors = $this->formValidator->validateDto($homeworkDto);
        
        if (!empty($errors)) {
            return new HomeworkErrorResponse(implode(' ', $errors));
        }

        $teacher = $this->teacherService->ensureTeacher();
        $assignedGroups = $this->gradeBookService->getGroupsAssignedToTeacher($teacher->getId());
        $homework = $this->homeworkService->getHomeworkById($id);

        if (!$homework) {
            return new HomeworkNotFoundResponse();
        }

        try {
            $this->homeworkService->updateHomework($homework, $homeworkDto, $teacher, $assignedGroups);
            $this->homeworkService->saveHomework($homework);
            
            return new HomeworkSuccessResponse('Homework has been updated successfully.');
        } catch (Exception $e) {
            return new HomeworkErrorResponse($e->getMessage());
        }
    }

    #[Route('/homework/delete/{id}', name: 'app_homework_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_TEACHER')]
    public function deleteHomework(int $id): JsonResponse
    {
        $teacher = $this->teacherService->ensureTeacher();

        try {
            $this->homeworkService->deleteHomework($id, $teacher);
            
            return new HomeworkSuccessResponse('Homework has been deleted successfully.');
        } catch (Exception $e) {
            return new HomeworkErrorResponse($e->getMessage());
        }
    }

    #[Route('/homework/status/{id}', name: 'app_homework_status', methods: ['PATCH'])]
    #[IsGranted('ROLE_TEACHER')]
    public function changeHomeworkStatus(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['status'])) {
            return new MissingStatusResponse();
        }

        $teacher = $this->teacherService->ensureTeacher();
        
        try {
            $newStatus = HomeworkStatus::from($data['status']);
            $homework = $this->homeworkService->changeHomeworkStatus($id, $newStatus, $teacher);
            
            return new HomeworkStatusChangeResponse($homework);
        } catch (Exception $e) {
            return new HomeworkErrorResponse($e->getMessage());
        }
    }
}
