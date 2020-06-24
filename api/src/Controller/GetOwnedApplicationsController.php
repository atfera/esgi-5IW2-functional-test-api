<?php

namespace App\Controller;

use App\Repository\ApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetOwnedApplicationsController extends AbstractController
{
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function __invoke()
    {
        $user = $this->getUser();

        $offers = $this->applicationRepository->findBy(['applicant' => $user->getId()]);

        return $offers;
    }
}
