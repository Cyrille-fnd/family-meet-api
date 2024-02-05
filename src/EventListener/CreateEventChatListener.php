<?php

namespace App\EventListener;

use App\Entity\Chat;
use App\Event\EventCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Uid\Uuid;

#[AsEventListener]
class CreateEventChatListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(
        EventCreatedEvent $event
    ): void {
        $familyEvent = $event->getEvent();

        $chat = new Chat();

        $chat
            ->setId(Uuid::v4()->jsonSerialize())
            ->setCreatedAt(new \DateTime())
            ->setEvent($familyEvent)
            ->addChatter($familyEvent->getHost());
        $this->entityManager->persist($chat);

        $this->entityManager->flush();
    }
}
