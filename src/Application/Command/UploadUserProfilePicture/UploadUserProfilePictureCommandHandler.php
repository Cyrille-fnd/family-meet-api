<?php

declare(strict_types=1);

namespace App\Application\Command\UploadUserProfilePicture;

use App\Application\CommandHandlerInterface;
use App\Application\EventDispatcherInterface;
use App\Domain\Event\UserProfilePictureUploadedEvent;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\FileUploaderInterface;

final readonly class UploadUserProfilePictureCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private FileUploaderInterface $fileUploader,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(UploadUserProfilePictureCommand $command): void
    {
        $user = $this->userRepository->get($command->id);
        $path = $this->fileUploader->upload($command->file);

        $user->setPictureUrl($path);

        $this->eventDispatcher->dispatch(
            new UserProfilePictureUploadedEvent(
                id: $command->id,
            )
        );
    }
}
