<?php

namespace App\Tests\Behat\Context\Traits;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

trait AuthTrait
{
    /**
     * The token to use with HTTP authentication
     *
     * @var string
     */
    protected $token;

    /**
     * @Given /^I authenticate with user "([^"]*)" and password "([^"]*)"$/
     */
    public function iAuthenticateWithEmailAndPassword($email, $password, UserPasswordEncoderInterface $encoder)
    {
        // $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        // $user = $em->getRepository(User::class)->findOneBy([
        //     'email' => $email,
        // ]);

        // if (!$user) {
        //     throw new \Exception('Wrong credentials');
        // }

        // $isPasswordValid = $encoder->isPasswordValid($user, $password);

        // if (!$isPasswordValid) {
        //     throw new \Exception('Wrong credentials');
        // }

        // // Return token ?
    }

    /**
     * @Given /^I am not authenticated$/
     */
    public function iNotAuthenticated()
    {
        if($this->arrayHas($this->requestHeaders, 'Authorization')) {
            unset($this->requestHeaders['Authorization']);
        }
    }

    /**
     * @Given /^I validate the account$/
     */
    public function iValidateAccount()
    {
        $payload = $this->getResponsePayload();

        $ressource = '/users/validation/' . $this->arrayGet($payload, 'token');

        $this->iRequest('GET', $ressource);
    }
}
