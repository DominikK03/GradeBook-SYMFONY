<?php

namespace App\Controller;

use App\Service\GradeBookService;
use App\Service\GradeService;
use App\Service\TeacherService;
use App\Service\UserDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GradeBookController extends AbstractController
{
    public function __construct(
        private readonly GradeBookService $gradeBookService,
        private readonly UserDataService $userDataService,
        private readonly GradeService $gradeService,
        private readonly TeacherService $teacherService
    )
    {
    }
    #[Route('/gradebook/{group}', name:'app_gradebook', defaults:['group' => ""])]
    public function gradeBook(Request $request): Response
    {
        $groupSelectionForm = $this->gradeBookService->generateGroupSelectionForm($this->userDataService->getCurrentUser()->getRoleEntity()->getId());
        $groupSelectionForm->handleRequest($request);
        if ($groupSelectionForm->isSubmitted() && $groupSelectionForm->isValid()) {
            $groupName = $groupSelectionForm->getData()['group'];
            return $this->redirectToRoute('app_gradebook', ['group' => $groupName]);
        }

        return $this->render('gradebook.html.twig', [
            'TopNavbarTitle' => 'navigation.gradebook',
            'userData' => $this->userDataService->getPerson(),
            'groupSelectionForm' => $groupSelectionForm->createView(),
            'students' => $this->gradeService->getStudentsWithGradesForTeacher($request->attributes->get('group'), $this->teacherService->ensureTeacher())
        ]);
    }

}
