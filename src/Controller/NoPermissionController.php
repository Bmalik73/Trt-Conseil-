<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoPermissionController extends AbstractController
{
    #[Route('/no-permission', name: 'app_no_permission')]
    public function index(Request $request): Response
    {
        $currentUser = $this->getUser();

        if (in_array('ROLE_ADMINISTRATOR', $currentUser->getRoles(), true)) {
            $route = '\\admin';
        } else if (in_array('ROLE_CONSULTANT', $currentUser->getRoles(), true)) {
            $route = '\\consultant';
        } else if (in_array('ROLE_RECRUITER', $currentUser->getRoles(), true)) {
            $route = '\\recruiter';
        } else if (in_array('ROLE_CANDIDATE', $currentUser->getRoles(), true)) {
            $route = '\\candidate';
        }

        return $this->render('no_permission/index.html.twig', [
            'route' => $route
        ]);
    }
}
