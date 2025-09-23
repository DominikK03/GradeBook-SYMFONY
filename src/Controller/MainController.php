<?php

namespace App\Controller;
use App\DataFixtures\TeacherFixtures;
use App\Enum\Subject;
use App\Repository\PersonRepository;
use App\Service\UserDataService;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainController extends AbstractController
{
    public function __construct(private UserDataService $userDataService)
    {
    }
    #[Route('/home', name: 'homepage')]
    public function index() : Response
    {
        return $this->render('dashboard.html.twig',[
            'TopNavbarTitle' => 'navigation.homepage',
            'userData' => $this->userDataService->parseUserData($this->userDataService->getPerson())
        ]);
    }
}
