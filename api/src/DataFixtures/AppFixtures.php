<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Status;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {

        $status1 = new Status();
        $status1->setName('Créé');
        $manager->persist($status1);

        $status2 = new Status();
        $status2->setName('Ouvert');
        $manager->persist($status2);

        $status3 = new Status();
        $status3->setName('En validation');
        $manager->persist($status3);

        $status4 = new Status();
        $status4->setName('RDV fixé');
        $manager->persist($status4);

        $status5 = new Status();
        $status5->setName('Accepté');
        $manager->persist($status5);

        $status6 = new Status();
        $status6->setName('Refusé');
        $manager->persist($status6);

        $user = new User();
        $user->setEmail('test@test.fr');
        $passwordEncoded = $this->encoder->encodePassword($user, 'test');
        $user->setPassword($passwordEncoded);

        $manager->persist($user);


        $manager->flush();
    }
}
