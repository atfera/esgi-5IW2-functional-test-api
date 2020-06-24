<?php
namespace App\DataFixtures\Faker\Provider;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordProvider
{
    public static function encodedPassword($user, $str, UserPasswordEncoderInterface $encoder)
    {
        return $encoder->encodePassword($user, $str);
    }
}
