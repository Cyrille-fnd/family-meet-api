<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Command\UploadUserProfilePicture\UploadUserProfilePictureCommand;
use App\Application\CommandBusInterface;
use App\Domain\ValueObject\Identity\UserId;
use App\Domain\ValueObject\UploadedFileInformation;
use App\Infrastructure\Bridge\Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

final readonly class UploadUserProfilePictureController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(
        #[ValueResolver('id')]
        UserId $id,
        Request $request,
        #[MapUploadedFile]
        SymfonyUploadedFile $profilePicture,
    ): JsonResponse {
        $this->commandBus->dispatch(new UploadUserProfilePictureCommand(
            id: $id,
            file: new UploadedFileInformation(UploadedFile::fromSymfonyUploadedFile($profilePicture))
        ));

        return new JsonResponse();
    }
}
