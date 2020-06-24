<?php

namespace App\Controller;

use App\Entity\Application;
use App\Repository\ApplicationRepository;
use App\Repository\OfferRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class OffersApplicationsController extends AbstractController
{
    private $entityManager;
    private $offerRepository;
    private $applicationRepository;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, OfferRepository $offerRepository,
                                ApplicationRepository $applicationRepository, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->offerRepository = $offerRepository;
        $this->applicationRepository = $applicationRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $data)
    {
        $idOffer = $data->get('id');
        $offer = $this->offerRepository->findOneBy(['id' => $idOffer]);

        $user = $this->getUser();

        $content = $data->getContent();
        $applicants = json_decode($content)->applicants;

        if ($offer) {
            if ($offer->getRecruiter()->getId() === $user->getId()) {

                foreach ($applicants as $applicantId) {
                    $applicant = $this->userRepository->findOneBy(['id' => $applicantId]);

                    if ($applicant !== null) {
                        $application =  $this->applicationRepository->findOneBy([
                                'applicant' => $applicant->getId(),
                                'offer' => $offer->getId()
                            ]
                        );
                        if ($application === null) {
                            $application = new Application();
                            $application->setApplicant($applicant);
                        }
                        $this->entityManager->persist($application);
                        $offer->addApplication($application);
                    }
                }

                $this->entityManager->persist($offer);
                $this->entityManager->flush();
            } else {
                throw new AccessDeniedException('');
            }

        } else {
            throw new NotFoundHttpException('');
        }

        return $offer;
    }
}
