<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Command\CreateMeet\CreateMeetCommand;
use App\Application\CommandBusInterface;
use App\Application\DTO\CreateMeetInputDto;
use App\Domain\ValueObject\Category;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\MeetId;
use App\Domain\ValueObject\Identity\UserId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

final readonly class CreateMeetController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(
        #[MapRequestPayload]
        CreateMeetInputDto $createMeetInputDto,
    ): JsonResponse {
        $meetId = MeetId::create();

        $this->commandBus->dispatch(new CreateMeetCommand(
            id: $meetId,
            hostId: UserId::fromString($createMeetInputDto->hostId),
            title: $createMeetInputDto->title,
            description: $createMeetInputDto->description,
            location: $createMeetInputDto->location,
            date: DateTimeImmutable::fromString($createMeetInputDto->date),
            category: Category::from($createMeetInputDto->category),
            maxGuests: $createMeetInputDto->maxGuests,
        ));

        return new JsonResponse(['id' => $meetId->value()], Response::HTTP_CREATED);
    }
}
