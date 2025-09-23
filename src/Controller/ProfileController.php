<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Response\PasswordChangeResponse\PasswordChangeErrorResponse;
use App\Response\PasswordChangeResponse\PasswordChangeSuccessResponse;
use App\Response\PasswordChangeResponse\PasswordChangeValidationErrorResponse;
use App\Service\UserDataService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    public function __construct(
        private UserDataService $userDataService,
        private UserService $userService
    ) {
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $changePasswordForm = $this->createForm(ChangePasswordFormType::class);
        
        return $this->render('profile/index.html.twig',[
            'userData' => $this->userDataService->parseUserData($this->userDataService->getPerson()),
            'TopNavbarTitle' => 'navigation.profile',
            'changePasswordForm' => $changePasswordForm->createView()
        ]);
    }

    #[Route('/profile/change-password', name: 'app_profile_change_password', methods: ['POST'])]
    public function changePassword(Request $request): JsonResponse
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userDataService->getCurrentUser();
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if (!$this->userService->changePassword($user, $currentPassword, $newPassword)) {
                return new PasswordChangeErrorResponse(
                    'Current password is incorrect',
                    ['currentPassword' => ['Current password is incorrect']]
                );
            }

            return new PasswordChangeSuccessResponse();
        }

        $errors = $this->userService->extractFormErrors($form);

        return new PasswordChangeValidationErrorResponse($errors);
    }
}
