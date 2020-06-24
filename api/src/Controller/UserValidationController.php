<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class UserValidationController
{
    private $entityManager;
    private $users;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $users, \Swift_Mailer $mailer)
    {
       $this->entityManager = $entityManager;
       $this->users = $users;
       $this->mailer = $mailer;
    }

    public function __invoke(Request $data)
    {
        $token = $data->get('token');

        $user = $this->users->findOneBy(['token' => $token]);

        if ($user) {
            $user
                ->setToken(null)
                ->setIsActive(true)
            ;
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $message = (new \Swift_Message('Votre inscription sur recruit-me est validée'))
                ->setFrom('no-reply@recruit-me.com')
                ->setTo($user->getEmail())
                ->setBody('Bienvenue sur recruit-me');
            $this->mailer->send($message);
        } else {
            $user = null;
        }

        return $user;
    }
}
