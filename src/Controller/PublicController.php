<?php

namespace App\Controller;

use App\Service\UserDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    #[Route('/', name:'app_public')]
    public function publicPage(): Response
    {

        return $this->render('public.html.twig');
    }
}
