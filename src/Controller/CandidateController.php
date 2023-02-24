<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Candidate;
use App\Entity\JobApplication;
use App\Repository\CandidateRepository;
use App\Repository\JobOfferRepository;
use App\Repository\JobApplicationRepository;
use App\Form\CandidateCreationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CandidateController extends AbstractController
{
    #[Route('/candidate', name: 'app_candidate')]
    public function index(CandidateRepository $candidateRepository, JobOfferRepository $jobOfferRepository, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $currentUser = $this->getUser();

        $candidateEmail = $currentUser->getEmail();
        $candidateFirstName = null;
        $candidateLastName = null;
        $candidateCV = '';
        $candidateApplications = [];
        $fileError = '';
        $candidateId = null;
        $candidates = $candidateRepository->findAll();
        $jobOffers = $jobOfferRepository->findBy(array('isApproved' => true));

        foreach ($candidates as $candidate) {
            if ($currentUser->getId() == $candidate->getUser()->getId()) {
                $candidateFirstName = $candidate->getFirstName();
                $candidateLastName = $candidate->getLastName();
                $candidateCV = $candidate->getCv();
                $candidateId = $candidate->getId();
                $candidateApplications = $candidate->getJobApplications();
            }
        }


        $newCandidate = new Candidate();
        $form = $this->createForm(CandidateCreationFormType::class, $newCandidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newCandidate->setUser($currentUser);
            $newCandidate->setFirstName($form->get('firstName')->getData());
            $newCandidate->setLastName($form->get('lastName')->getData());

            $cv = $form->get('cv')->getData();
            $originalFilename = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$cv->guessExtension();
            try {
                $cv->move(
                    $this->getParameter('cv_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                $fileError = $e;
            }

            $newCandidate->setCv($newFilename);

            $entityManager->persist($newCandidate);
            $entityManager->flush();

            return $this->redirectToRoute('app_candidate_created', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('candidate/index.html.twig', [
            'candidateEmail' => $candidateEmail,
            'candidateFirstName' => $candidateFirstName,
            'candidateLastName' => $candidateLastName,
            'candidateCV' => $candidateCV,
            'candidateId' => $candidateId,
            'jobOffers' => $jobOffers,
            'candidateApplications' => $candidateApplications,
            'fileError' => $fileError,
            'candidateCreationForm' => $form->createView()
        ]);
    }

    #[Route('/candidate/candidate_created', name: 'app_candidate_created')]
    public function candidateCreated(CandidateRepository $candidateRepository): Response
    {
        $currentUser = $this->getUser();

        $candidates = $candidateRepository->findAll();

        foreach ($candidates as $candidate) {
            if ($currentUser->getId() == $candidate->getUser()->getId()) {
                $candidateFirstName = $candidate->getFirstName();
                $candidateLastName = $candidate->getLastName();
            }
        }

        return $this->render('candidate/candidate_created.html.twig', [
            'candidateFirstName' => $candidateFirstName,
            'candidateLastName' => $candidateLastName
        ]);
    }

    #[Route('/candidate/job_application_created-{candidateId}-{jobOfferId}', name: 'app_job_application_created')]
    public function jobApplicationCreated(CandidateRepository $candidateRepository, JobOfferRepository $jobOfferRepository, EntityManagerInterface $entityManager, int $candidateId, int $jobOfferId): Response
    {
        $currentUser = $this->getUser();

        $candidates = $candidateRepository->findAll();

        foreach ($candidates as $candidate) {
            if ($currentUser->getId() == $candidate->getUser()->getId()) {
                $candidateFirstName = $candidate->getFirstName();
                $candidateLastName = $candidate->getLastName();
            }
        }

        $jobOffer = $jobOfferRepository->findOneBy(array('id' => $jobOfferId));

        $jobApplication = new JobApplication();
        $jobApplication->setCandidate($candidateRepository->findOneBy(array('id' => $candidateId)));
        $jobApplication->setJobOffer($jobOffer);
        $jobApplication->setIsApproved(false);

        $entityManager->persist($jobApplication);
        $entityManager->flush();

        return $this->render('candidate/job_application_created.html.twig', [
            'jobOffer' => $jobOffer,
            'candidateFirstName' => $candidateFirstName,
            'candidateLastName' => $candidateLastName
        ]);
    }

    #[Route('/candidate/job_application_canceled-{id}', name: 'app_job_application_canceled', methods: ['GET', 'POST'])]
    public function cancel_job_application(CandidateRepository $candidateRepository, JobApplicationRepository $jobApplicationRepository, int $id): Response
    {
        $currentUser = $this->getUser();

        $candidates = $candidateRepository->findAll();

        foreach ($candidates as $candidate) {
            if ($currentUser->getId() == $candidate->getUser()->getId()) {
                $candidateFirstName = $candidate->getFirstName();
                $candidateLastName = $candidate->getLastName();
            }
        }

        $jobApplication = $jobApplicationRepository->findOneBy(array('id' => $id));
        $jobOffer = $jobApplication->getJobOffer();

        $jobApplicationRepository->remove($jobApplication, true);

        return $this->render('candidate/job_application_canceled.html.twig', [
            'jobOffer' => $jobOffer,
            'candidateFirstName' => $candidateFirstName,
            'candidateLastName' => $candidateLastName
        ]);
    }
}
