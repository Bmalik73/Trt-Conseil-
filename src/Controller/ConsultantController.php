<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\JobOffer;
use App\Entity\JobApplication;
use App\Repository\ConsultantRepository;
use App\Repository\JobApplicationRepository;
use App\Repository\UserRepository;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultantController extends AbstractController
{
    #[Route('/consultant', name: 'app_consultant')]
    public function index(ConsultantRepository $consultantRepository, UserRepository $userRepository, JobOfferRepository $jobOfferRepository, JobApplicationRepository $jobApplicationRepository): Response
    {
        $currentUser = $this->getUser();

        $consultants = $consultantRepository->findAll();

        foreach ($consultants as $consultant) {
            if ($currentUser->getId() == $consultant->getUser()->getId()) {
                $consultFirstName = $consultant->getFirstName();
                $consultLastName = $consultant->getLastName();
            }
        }

        $candidates = $userRepository->findBy(array('userType' => 4), array('id' => 'ASC'));
        $unauthorizedCandidates = [];

        foreach ($candidates as $candidate) {
            if ($candidate->getRoles() == ["ROLE_UNAUTHORIZED"]) {
                array_push($unauthorizedCandidates, $candidate);
            }
        }

        $recruiters = $userRepository->findBy(array('userType' => 3), array('id' => 'ASC'));
        $unauthorizedRecruiters = [];

        foreach ($recruiters as $recruiter) {
            if ($recruiter->getRoles() == ["ROLE_UNAUTHORIZED"]) {
                array_push($unauthorizedRecruiters, $recruiter);
            }
        }

        $jobOffers = $jobOfferRepository->findAll();
        $jobOffersToApprove = [];

        foreach ($jobOffers as $jobOffer) {
            if ($jobOffer->isIsApproved() == false) {
                array_push($jobOffersToApprove, $jobOffer);
            }
        }

        $jobApplications = $jobApplicationRepository->findAll();
        $jobApplicationsToApprove = [];

        foreach ($jobApplications as $jobApplication) {
            if ($jobApplication->isIsApproved() == false) {
                array_push($jobApplicationsToApprove, $jobApplication);
            }
        }

        return $this->render('consultant/index.html.twig', [
            'unauthorizedCandidates' => $unauthorizedCandidates,
            'unauthorizedRecruiters' => $unauthorizedRecruiters,
            'jobOffersToApprove' => $jobOffersToApprove,
            'jobApplicationsToApprove' => $jobApplicationsToApprove,
            'consultFirstName' => $consultFirstName,
            'consultLastName' => $consultLastName
        ]);
    }

    #[Route('/consultant/grant-candidate-{id}', name: 'app_consultant_grant_candidate', methods: ['GET', 'POST'])]
    public function grant_candidate(ConsultantRepository $consultantRepository, User $user, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        $consultants = $consultantRepository->findAll();

        foreach ($consultants as $consultant) {
            if ($currentUser->getId() == $consultant->getUser()->getId()) {
                $consultFirstName = $consultant->getFirstName();
                $consultLastName = $consultant->getLastName();
            }
        }

        $user->setRoles(["ROLE_CANDIDATE"]);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('consultant/grant_candidate.html.twig', [
            'candidate' => $user,
            'consultFirstName' => $consultFirstName,
            'consultLastName' => $consultLastName
        ]);
    }

    #[Route('/consultant/grant-recruiter-{id}', name: 'app_consultant_grant_recruiter', methods: ['GET', 'POST'])]
    public function grant_recruiter(ConsultantRepository $consultantRepository, User $user, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        $consultants = $consultantRepository->findAll();

        foreach ($consultants as $consultant) {
            if ($currentUser->getId() == $consultant->getUser()->getId()) {
                $consultFirstName = $consultant->getFirstName();
                $consultLastName = $consultant->getLastName();
            }
        }

        $user->setRoles(["ROLE_RECRUITER"]);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('consultant/grant_recruiter.html.twig', [
            'recruiter' => $user,
            'consultFirstName' => $consultFirstName,
            'consultLastName' => $consultLastName
        ]);
    }

    #[Route('/consultant/deny-user-{id}', name: 'app_consultant_deny_user', methods: ['GET', 'POST'])]
    public function deny_user(ConsultantRepository $consultantRepository, User $user, UserRepository $userRepository): Response
    {
        $currentUser = $this->getUser();

        $consultants = $consultantRepository->findAll();

        foreach ($consultants as $consultant) {
            if ($currentUser->getId() == $consultant->getUser()->getId()) {
                $consultFirstName = $consultant->getFirstName();
                $consultLastName = $consultant->getLastName();
            }
        }

        $userRepository->remove($user, true);

        return $this->render('consultant/deny_user.html.twig', [
            'user' => $user,
            'consultFirstName' => $consultFirstName,
            'consultLastName' => $consultLastName
        ]);
    }

    #[Route('/consultant/grant-job-offer-{id}', name: 'app_consultant_grant_job_offer', methods: ['GET', 'POST'])]
    public function grant_job_offer(ConsultantRepository $consultantRepository, JobOffer $jobOffer, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        $consultants = $consultantRepository->findAll();

        foreach ($consultants as $consultant) {
            if ($currentUser->getId() == $consultant->getUser()->getId()) {
                $consultFirstName = $consultant->getFirstName();
                $consultLastName = $consultant->getLastName();
            }
        }

        $jobOffer->setIsApproved(true);
        $entityManager->persist($jobOffer);
        $entityManager->flush();

        return $this->render('consultant/grant_job_offer.html.twig', [
            'jobOffer' => $jobOffer,
            'consultFirstName' => $consultFirstName,
            'consultLastName' => $consultLastName
        ]);
    }

    #[Route('/consultant/deny-job-offer-{id}', name: 'app_consultant_deny_job_offer', methods: ['GET', 'POST'])]
    public function deny_job_offer(ConsultantRepository $consultantRepository, JobOffer $jobOffer, JobOfferRepository $jobOfferRepository): Response
    {
        $currentUser = $this->getUser();

        $consultants = $consultantRepository->findAll();

        foreach ($consultants as $consultant) {
            if ($currentUser->getId() == $consultant->getUser()->getId()) {
                $consultFirstName = $consultant->getFirstName();
                $consultLastName = $consultant->getLastName();
            }
        }

        $jobOfferRepository->remove($jobOffer, true);

        return $this->render('consultant/deny_job_offer.html.twig', [
            'jobOffer' => $jobOffer,
            'consultFirstName' => $consultFirstName,
            'consultLastName' => $consultLastName
        ]);
    }

    #[Route('/consultant/grant-job-application-{id}', name: 'app_consultant_grant_job_application', methods: ['GET', 'POST'])]
    public function grant_job_application(ConsultantRepository $consultantRepository, JobApplication $jobApplication, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        $consultants = $consultantRepository->findAll();

        foreach ($consultants as $consultant) {
            if ($currentUser->getId() == $consultant->getUser()->getId()) {
                $consultFirstName = $consultant->getFirstName();
                $consultLastName = $consultant->getLastName();
            }
        }

        $jobApplication->setIsApproved(true);
        $entityManager->persist($jobApplication);
        $entityManager->flush();

        return $this->render('consultant/grant_job_application.html.twig', [
            'jobApplication' => $jobApplication,
            'consultFirstName' => $consultFirstName,
            'consultLastName' => $consultLastName
        ]);
    }

    #[Route('/consultant/deny-job-application-{id}', name: 'app_consultant_deny_job_application', methods: ['GET', 'POST'])]
    public function deny_job_application(ConsultantRepository $consultantRepository, JobApplication $jobApplication, JobApplicationRepository $jobApplicationRepository): Response
    {
        $currentUser = $this->getUser();

        $consultants = $consultantRepository->findAll();

        foreach ($consultants as $consultant) {
            if ($currentUser->getId() == $consultant->getUser()->getId()) {
                $consultFirstName = $consultant->getFirstName();
                $consultLastName = $consultant->getLastName();
            }
        }

        $jobApplicationRepository->remove($jobApplication, true);

        return $this->render('consultant/deny_job_application.html.twig', [
            'jobApplication' => $jobApplication,
            'consultFirstName' => $consultFirstName,
            'consultLastName' => $consultLastName
        ]);
    }
}
