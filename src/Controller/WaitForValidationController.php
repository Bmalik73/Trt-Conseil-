<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WaitForValidationController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    #[Route('/wait_for_validation', name: 'app_wait_for_validation')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (in_array('ROLE_ADMINISTRATOR', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        } else if (in_array('ROLE_CONSULTANT', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_consultant'));
        } else if (in_array('ROLE_RECRUITER', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_recruiter'));
        } else if (in_array('ROLE_CANDIDATE', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_candidate'));
        }
        
        return $this->render('wait_for_validation/index.html.twig');
    }
}
