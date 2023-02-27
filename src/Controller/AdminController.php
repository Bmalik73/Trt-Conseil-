<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Consultant;
use App\Entity\Administrator;
use App\Repository\ConsultantRepository;
use App\Repository\AdministratorRepository;
use App\Form\ConsultantCreationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(AdministratorRepository $administratorRepository, ConsultantRepository $consultantRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();
        $adminFirstName = null;
        $adminLastName = null;
        $administrators = $administratorRepository->findAll();
        $consultants = $consultantRepository->findAll();

        foreach ($administrators as $administrator) {
            if ($currentUser->getId() == $administrator->getId()) {
                $adminFirstName = $administrator->getFirstName();
                $adminLastName = $administrator->getLastName();
            }
        }

        $user = new User();
        $consultant = new Consultant();
        $form = $this->createForm(ConsultantCreationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setIsApproved(true);
            $user->setRoles(["ROLE_CONSULTANT"]);
            $user->setUserType(2);

            $consultant->setFirstName($form->get('firstName')->getData());
            $consultant->setLastName($form->get('lastName')->getData());
            $consultant->setUser($user);
            
            $entityManager->persist($user);
            $entityManager->persist($consultant);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_consultant_created', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/index.html.twig', [
            'consultants' => $consultants,
            'adminFirstName' => $adminFirstName,
            'adminLastName' => $adminLastName,
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('/admin/consultant_created', name: 'app_admin_consultant_created')]
    public function consultantCreated(AdministratorRepository $administratorRepository, ConsultantRepository $consultantRepository): Response
    {
        $currentUser = $this->getUser();
        $adminFirstName = null;
        $adminLastName = null;

        $administrators = $administratorRepository->findAll();
        $lastConsultant = $consultantRepository->findOneBy(array(), array('id' => 'DESC'));

        foreach ($administrators as $administrator) {
            if ($currentUser->getId() == $administrator->getId()) {
                $adminFirstName = $administrator->getFirstName();
                $adminLastName = $administrator->getLastName();
            }
        }

        return $this->render('admin/consultant_created.html.twig', [
            'lastConsultant' => $lastConsultant,
            'adminFirstName' => $adminFirstName,
            'adminLastName' => $adminLastName
        ]);
    }
}




