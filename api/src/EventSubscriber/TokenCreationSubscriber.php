<?php
namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Offer;
use App\Entity\Status;
use App\Repository\StatusRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class TokenCreationSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $statusRepository;

    public function __construct(\Swift_Mailer $mailer, StatusRepository $statusRepository)
    {
        $this->mailer = $mailer;
        $this->statusRepository = $statusRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['generateToken', EventPriorities::PRE_WRITE],
        ];
    }

    public function generateToken(ViewEvent $event): void
    {
        $offer = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$offer instanceof Offer || Request::METHOD_PATCH !== $method) {
            return;
        }
        foreach ($offer->getApplications() as $application) {
            if ($application->getStatus() === null) {

                $token = openssl_random_pseudo_bytes(10);
                $token = bin2hex($token);
                $application->setToken($token);

                $status = $this->statusRepository->findOneBy(['name' => 'Créé']);
                $application->setStatus($status);

                $message = (new \Swift_Message('Validation de votre condidature à l\'offre ' . $offer->getName()))
                    ->setFrom('no-reply@recruit-me.com')
                    ->setTo($application->getApplicant()->getEmail())
                    ->setBody('https://'.$this->requestStack->getCurrentRequest()->getHttpHost().'/applications/validation/'.$token);

                $this->mailer->send($message);
            }
        }
    }
}
