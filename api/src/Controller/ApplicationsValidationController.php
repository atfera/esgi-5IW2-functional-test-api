<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\User;
use App\Repository\ApplicationRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ApplicationsValidationController
{
    private $entityManager;
    private $applicationRepository;
    private $statusRepository;

    public function __construct(EntityManagerInterface $entityManager, ApplicationRepository $applicationRepository, StatusRepository $statusRepository)
    {
        $this->entityManager = $entityManager;
        $this->applicationRepository = $applicationRepository;
        $this->statusRepository = $statusRepository;
    }

    public function __invoke(Request $data)
    {
        $token = $data->get('token');

        $application = $this->applicationRepository->findOneBy(['token' => $token]);

        if ($application) {
            $status = $this->statusRepository->findOneBy(['name' => 'Ouvert']);
            $application->setStatus($status);
            $this->entityManager->persist($application);
            $this->entityManager->flush();
        } else {
            $application = null;
        }

        return $application;
    }
}
