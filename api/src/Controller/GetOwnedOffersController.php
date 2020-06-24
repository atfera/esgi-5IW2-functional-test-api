<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetOwnedOffersController extends AbstractController
{
    private $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function __invoke()
    {
        $user = $this->getUser();

        $offers = $this->offerRepository->findBy(['recruiter' => $user->getId()]);

        return $offers;
    }
}
