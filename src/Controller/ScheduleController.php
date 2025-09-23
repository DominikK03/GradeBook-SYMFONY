<?php

namespace App\Controller;

use App\Service\ScheduleService;
use App\Service\UserDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ScheduleController extends AbstractController
{
    public function __construct(private readonly UserDataService $userDataService,
    private readonly ScheduleService $scheduleService)
    {
    }

    #[Route('/schedule', name: 'app_schedule')]
    public function index(): Response
    {

        return $this->render('schedule/index.html.twig', [
            'TopNavbarTitle' => 'navigation.schedule',
            'userData' => $this->userDataService->getPerson(),
            'scheduleData' => $this->scheduleService->getSchedule($this->userDataService->getCurrentUser()->getRoleEntity())
        ]);
    }
}
