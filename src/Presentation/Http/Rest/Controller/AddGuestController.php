<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Command\AddGuest\AddGuestCommand;
use App\Application\CommandBusInterface;
use App\Application\DTO\AddGuestInputDto;
use App\Domain\ValueObject\Identity\MeetId;
use App\Domain\ValueObject\Identity\UserId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

final readonly class AddGuestController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(
        #[ValueResolver('id')]
        MeetId $id,
        #[MapRequestPayload]
        AddGuestInputDto $dto,
    ): JsonResponse {
        $this->commandBus->dispatch(new AddGuestCommand(
            meetId: $id,
            userId: UserId::fromString($dto->userId),
        ));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
