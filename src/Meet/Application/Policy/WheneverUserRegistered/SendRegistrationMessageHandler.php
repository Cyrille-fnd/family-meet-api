<?php

declare(strict_types=1);

namespace App\Meet\Application\Policy\WheneverUserRegistered;

use App\Entity\User;
use App\Meet\Application\EventHandlerInterface;
use App\Meet\Domain\Event\UserRegisteredEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final readonly class SendRegistrationMessageHandler implements EventHandlerInterface
{
    public function __construct(
        public MailerInterface $mailer,
        public EntityManagerInterface $em,
    ) {
    }

    public function __invoke(UserRegisteredEvent $event): void
    {
        /** @var User|null */
        $user = $this->em->getRepository(User::class)->find($event->userId);

        if (null === $user) {
            return;
        }

        $email = (new Email())
            // force email cause sandbox only allow verified emails for 'from' and 'to'
            ->from('ferandc@gmail.com')
            ->to('ferandc@gmail.com')
            ->subject(sprintf('Bienvenue %s %s !!', $user->getFirstname(), $user->getLastname()))
            ->html('<p>Bonjour '.$user->getFirstname().',</p><p>Votre compte a été créé avec succès !!</p>');

        $this->mailer->send($email);
    }
}
