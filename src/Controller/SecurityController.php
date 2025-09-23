<?php

namespace App\Controller;

use App\Service\UserDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(private readonly UserDataService $userDataService)
    {
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
    }

    #[Route(path: '/access-denied', name: 'app_access_denied')]
    public function accessDenied(): Response
    {
        return $this->render('security/access_denied.html.twig', [
            'title' => 'Access Denied',
            'message' => 'You do not have permission to view this page.',
            'userData' => $this->userDataService->getPerson(),
            'TopNavbarTitle' => 'navigation.access_denied',
            'suggestions' => [
                'Back to homepage' => 'homepage',
                'Check your profile' => 'app_profile',
                'Logout' => 'app_logout'
            ]
        ]);
    }
}
