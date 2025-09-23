<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use App\Service\GradeBookService;
use App\Service\GradeService;
use App\Service\StudentService;
use App\Service\UserDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StudentGradesController extends AbstractController
{
    public function __construct(
        private readonly StudentService $studentService,
        private readonly UserDataService $userDataService
    )
    {
    }

    #[Route('/student/grades', name: 'app_student_grades')]
    public function index(): Response
    {
        return $this->render('student_grades/index.html.twig', [
            'TopNavbarTitle' => 'navigation.my_grades',
            'userData' => $this->userDataService->getCurrentUser()->getPerson(),
            'subjects' => $this->studentService->getStudentSubjects($this->userDataService->getCurrentUser()->getRoleEntity()),
            'grades' => $this->studentService->getStudentWithGradesById($this->userDataService->getCurrentUser()->getRoleEntity())
        ]);
    }
}
