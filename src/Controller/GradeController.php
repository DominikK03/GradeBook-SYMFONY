<?php

namespace App\Controller;

use App\Exception\GradeAccessDeniedException;
use App\Exception\GradeNotFoundException;
use App\Exception\ZeroTeacherSpecializationsException;
use App\Service\GradeService;
use App\Service\StudentService;
use App\Service\TeacherService;
use App\Service\UserDataService;
use App\Validator\FormValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use ValueError;

class GradeController extends AbstractController
{
    public function __construct(
        private readonly GradeService    $gradeService,
        private readonly StudentService  $studentService,
        private readonly FormValidator   $formValidationService,
        private readonly TeacherService  $teacherService
    )
    {
    }

    #[Route('/grade/add', name: 'app_grade_add', methods: ['POST'])]
    public function addGrade(Request $request): Response
    {
        $gradeData = $request->request->all()['grade'];

        if (!$gradeData) {
            $this->addFlash('error', sprintf('%s', 'Invalid form data.'));
            return $this->redirectBack($request);
        }
        $validationErrors = $this->formValidationService->validateGradeForm($gradeData);
        if (!empty($validationErrors)){
            foreach ($validationErrors as $error){
                $this->addFlash('error', sprintf('%s', $error));
            }
            return $this->redirectBack($request);
        }
        unset($gradeData['_token']);

        $student = $this->studentService->getStudentById((int)$gradeData['student_id']);
        $teacher = $this->teacherService->ensureTeacher();

        if (!$student || !$teacher) {
            $this->addFlash('error', sprintf('%s', 'Student or teacher not found.'));
            return $this->redirectBack($request);
        }

        try {
            $grade = $this->gradeService->createGrade($gradeData, $student, $teacher);
            $this->gradeService->saveGrade($grade);

            $this->addFlash('success', sprintf('%s', 'Grade has been added successfully.'));
        } catch (ZeroTeacherSpecializationsException $e) {
            $this->addFlash('error', sprintf('%s', $e->getMessage()));
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', sprintf('%s', 'Invalid grade or weight value.'));
        } catch (ValueError $e) {
            $this->addFlash('error', sprintf('%s', 'Invalid grade type.'));
        } catch (\Exception $e) {
            $this->addFlash('error', sprintf('%s', 'An unexpected error occurred while adding the grade.'));
        }

        return $this->redirectBack($request);
    }
    private function redirectBack(Request $request): Response
    {
        $referer = $request->headers->get('referer');

        if ($referer && $this->isValidReferer($referer, $request)) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('app_gradebook');
    }

    private function isValidReferer(string $referer, Request $request): bool
    {
        $parsedReferer = parse_url($referer);
        $currentHost = $request->getHost();

        if (!$parsedReferer || !isset($parsedReferer['host'])) {
            return false;
        }

        if ($parsedReferer['host'] !== $currentHost) {
            return false;
        }

        $path = $parsedReferer['path'] ?? '/';
        return str_starts_with($path, '/gradebook');
    }

    #[Route('/grade/{id}', name: 'app_grade_get', methods: ['GET'])]
    public function getGrade(int $id): JsonResponse
    {
        $grade = $this->gradeService->getGradeById($id);
        if (!$grade) {
            return $this->json(['error' => 'Grade not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json([
            'id' => $grade->getId(),
            'value' => $grade->getValue(),
            'type' => $grade->getType(),
            'wage' => $grade->getWage(),
            'date' => $grade->getDate()->format('Y-m-d'),
            'description' => $grade->getDescription(),
            'student_id' => $grade->getStudent()->getId(),
            'student_display' => sprintf("%s %s", $grade->getStudent()->getPerson()->getFirstName(), $grade->getStudent()->getPerson()->getLastName())
        ]);
    }

    #[Route('grade/{id}/edit', name: 'app_grade_edit', methods: ['POST'])]
    public function editGrade(int $id, Request $request): Response
    {
        $gradeData = $request->request->all()['grade'];
        $validationErrors = $this->formValidationService->validateGradeForm($gradeData);
        if (!empty($validationErrors)){
            foreach ($validationErrors as $error){
                $this->addFlash('error', sprintf('%s', $error));
            }
            return $this->redirectBack($request);
        }
        unset($gradeData['_token']);

        try {
            $grade = $this->gradeService->updateGrade($id, $gradeData, $this->teacherService->ensureTeacher()->getId());
            $this->gradeService->saveGrade($grade);
            $this->addFlash('success', sprintf('%s', 'Grade has been updated successfully.'));
        } catch (GradeNotFoundException $e) {
            $this->addFlash('error', sprintf('%s', $e->getMessage()));
        } catch (GradeAccessDeniedException $e) {
            $this->addFlash('error', sprintf('%s', $e->getMessage()));
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', sprintf('%s', 'Invalid grade or weight value.'));
        } catch (ValueError $e) {
            $this->addFlash('error', sprintf('%s', 'Invalid grade type.'));
        } catch (\Exception $e) {
            $this->addFlash('error', sprintf('%s', 'An unexpected error occurred while updating the grade.'));
        }
        return $this->redirectBack($request);
    }
    #[Route('grade/{id}/delete', name: 'app_grade_delete', methods: ['DELETE'])]
    public function deleteGrade(int $id, Request $request): Response
    {
        try {
            $this->gradeService->deleteGrade($id, $this->teacherService->ensureTeacher()->getId());
            $this->addFlash('success', sprintf('%s', 'Grade has been deleted'));
        } catch (GradeNotFoundException $e) {
            $this->addFlash('error', sprintf('%s', $e->getMessage()));
        } catch (GradeAccessDeniedException $e) {
            $this->addFlash('error', sprintf('%s', $e->getMessage()));
        } catch (\Exception $e) {
            $this->addFlash('error', sprintf('%s', 'An unexpected error occurred while deleting the grade.'));
        }
        return $this->redirectBack($request);
    }
}
