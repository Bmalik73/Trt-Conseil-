<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recruiter;
use App\Entity\JobOffer;
use App\Repository\RecruiterRepository;
use App\Repository\JobOfferRepository;
use App\Repository\JobApplicationRepository;
use App\Form\RecruiterCreationFormType;
use App\Form\JobOfferCreationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecruiterController extends AbstractController
{
    #[Route('/recruiter', name: 'app_recruiter')]
    public function index(RecruiterRepository $recruiterRepository, JobOfferRepository $jobOfferRepository, JobApplicationRepository $jobApplicationRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        $recruitEmail = $currentUser->getEmail();
        $recruitCompany = '';
        $recruitAddress = '';
        $recruitJobOffers = [];
        $recruiters = $recruiterRepository->findAll();

        foreach ($recruiters as $recruiter) {
            if ($currentUser->getId() == $recruiter->getUser()->getId()) {
                $currentRecruiter = $recruiter;
                $recruitCompany = $recruiter->getCompany();
                $recruitAddress = $recruiter->getAddress();
                $recruitJobOffers = $recruiter->getJobOffers();
            }
        }

        $newRecruiter = new Recruiter();
        $form = $this->createForm(RecruiterCreationFormType::class, $newRecruiter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRecruiter->setUser($currentUser);
            $newRecruiter->setCompany($form->get('company')->getData());
            $newRecruiter->setAddress($form->get('address')->getData());

            $entityManager->persist($newRecruiter);
            $entityManager->flush();

            return $this->redirectToRoute('app_recruiter_created', [], Response::HTTP_SEE_OTHER);
        }

        $newJobOffer = new JobOffer();
        $jobForm = $this->createForm(JobOfferCreationFormType::class, $newJobOffer);
        $jobForm->handleRequest($request);

        if ($jobForm->isSubmitted() && $jobForm->isValid()) {
            $newJobOffer->setTitle($jobForm->get('title')->getData());
            $newJobOffer->setDescription($jobForm->get('description')->getData());
            $newJobOffer->setIsApproved(false);
            $newJobOffer->setCompany($currentRecruiter);

            $entityManager->persist($newJobOffer);
            $entityManager->flush();

            $newJobOfferId = $newJobOffer->getId();

            return $this->redirectToRoute('app_job_offer_created', ['id' => $newJobOfferId], Response::HTTP_SEE_OTHER);
        }

        $jobApplications = $jobApplicationRepository->findAll();

        return $this->render('recruiter/index.html.twig', [
            'recruitEmail' => $recruitEmail,
            'recruitCompany' => $recruitCompany,
            'recruitAddress' => $recruitAddress,
            'recruitJobOffers' => $recruitJobOffers,
            'jobApplications' => $jobApplications,
            'recruiterCreationForm' => $form->createView(),
            'jobOfferCreationForm' => $jobForm->createView()
        ]);
    }

    #[Route('recruiter/recruiter_created', name: 'app_recruiter_created')]
    public function recruiterCreated(RecruiterRepository $recruiterRepository): Response
    {
        $currentUser = $this->getUser();

        $recruiters = $recruiterRepository->findAll();

        foreach ($recruiters as $recruiter) {
            if ($currentUser->getId() == $recruiter->getUser()->getId()) {
                $recruitCompany = $recruiter->getCompany();
                $recruitAddress = $recruiter->getAddress();
            }
        }

        return $this->render('recruiter/recruiter_created.html.twig', [
            'recruitCompany' => $recruitCompany,
            'recruitAddress' => $recruitAddress
        ]);
    }

    #[Route('recruiter/job_offer_created-{id}', name: 'app_job_offer_created')]
    public function jobOfferCreated(JobOfferRepository $jobOfferRepository, RecruiterRepository $recruiterRepository, int $id): Response
    {
        $currentUser = $this->getUser();

        $recruiters = $recruiterRepository->findAll();

        foreach ($recruiters as $recruiter) {
            if ($currentUser->getId() == $recruiter->getUser()->getId()) {
                $recruitCompany = $recruiter->getCompany();
                $recruitAddress = $recruiter->getAddress();
            }
        }

        $jobOffer = $jobOfferRepository->findOneBy(array('id' => $id));

        return $this->render('recruiter/job_offer_created.html.twig', [
            'jobOffer' => $jobOffer,
            'recruitCompany' => $recruitCompany,
            'recruitAddress' => $recruitAddress
        ]);
    }

    #[Route('recruiter/cancel-job-offer-{id}', name: 'app_cancel_job_offer', methods: ['GET', 'POST'])]
    public function cancel_job_offer(RecruiterRepository $recruiterRepository, JobOffer $jobOffer, JobOfferRepository $jobOfferRepository): Response
    {
        $currentUser = $this->getUser();

        $recruiters = $recruiterRepository->findAll();

        foreach ($recruiters as $recruiter) {
            if ($currentUser->getId() == $recruiter->getUser()->getId()) {
                $recruitCompany = $recruiter->getCompany();
                $recruitAddress = $recruiter->getAddress();
            }
        }

        $jobOfferRepository->remove($jobOffer, true);

        return $this->render('recruiter/cancel_job_offer.html.twig', [
            'jobOffer' => $jobOffer,
            'recruitCompany' => $recruitCompany,
            'recruitAddress' => $recruitAddress
        ]);
    }
}
