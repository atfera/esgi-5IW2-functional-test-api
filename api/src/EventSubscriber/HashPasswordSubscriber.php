<?php
    namespace App\EventSubscriber;

    use ApiPlatform\Core\EventListener\EventPriorities;
    use App\Entity\User;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpKernel\Event\ViewEvent;
    use Symfony\Component\HttpKernel\KernelEvents;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    use Symfony\Component\HttpFoundation\RequestStack;

    final class HashPasswordSubscriber implements EventSubscriberInterface
    {
        private $passwordEncoder;
        private $mailer;
        private $token;
        private $requestStack;

        public function __construct(RequestStack $requestStack, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
        {
            $this->requestStack = $requestStack;
            $this->passwordEncoder = $passwordEncoder;
            $this->mailer = $mailer;
            $this->token = uniqid();
        }

        public static function getSubscribedEvents()
        {
            return [
                KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE],
            ];
        }

        public function encodePassword(ViewEvent $event): void
        {
            $user = $event->getControllerResult();
            $method = $event->getRequest()->getMethod();

            if (!$user instanceof User || Request::METHOD_POST !== $method) {
                return;
            }

            $passwordEncoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());

            $user->setToken($this->token);
            $user->setPassword($passwordEncoded);

            $message = (new \Swift_Message('Validation de votre compte utilisateur'))
                ->setFrom('no-reply@recruit-me.com')
                ->setTo($user->getEmail())
                ->setBody('https://'.$this->requestStack->getCurrentRequest()->getHttpHost().'/users/validation/'.$this->token);
            $this->mailer->send($message);
        }
    }
