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
    public function iAuthenticateWithEmailAndPassword($email, $password)
    {
        $resource = '/authentication_token';

        $payload = new \stdClass();
        $payload->email = $this->referenceManager->compile($email);
        $payload->password = $password;

        $this->iRequest('POST', $resource, $payload);

        $responsePayload = $this->getResponsePayload();

        $this->token = $this->arrayGet($responsePayload, 'token');

        $this->iSetTheHeaderToBe('Authorization', 'Bearer ' . $this->token);
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
