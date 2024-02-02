<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\RegisteredUserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class SendRegistrationMessageHandler
{
    private MailerInterface $mailer;

    private EntityManagerInterface $em;

    public function __construct(
        MailerInterface $mailer,
        EntityManagerInterface $em
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public function __invoke(
        RegisteredUserEvent $event
    ): void {
        /** @var User|null */
        $user = $this->em->getRepository(User::class)->find($event->getUserId());

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
