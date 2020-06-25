<?php

namespace App\Tests\Behat\Manager;

use Twig\Environment;

class ReferenceManager
{

    private $references;
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function set($key, $value): void
    {
        $this->references[$key] = $value;
    }

    public function compile($string): string
    {
        return $this->twig->render(
            $this->twig->createTemplate($string),
            $this->references
        );
    }
}
