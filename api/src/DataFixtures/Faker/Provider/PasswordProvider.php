<?php
namespace App\DataFixtures\Faker\Provider;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProvider
{

    private static $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        self::$passwordEncoder = $passwordEncoder;
    }

    public static function encodedPassword($pwd)
    {
        $user = new User();

        return $passwordEncoder = self::$passwordEncoder->encodePassword($user, $pwd);
    }

    public static function tokenGen()
    {
        return $token = uniqid();
    }
}