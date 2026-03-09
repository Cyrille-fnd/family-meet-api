<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Command\UpdateMeet\UpdateMeetCommand;
use App\Application\CommandBusInterface;
use App\Application\DTO\UpdateMeetInputDto;
use App\Domain\ValueObject\Category;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\MeetId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

final readonly class UpdateMeetController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(
        #[ValueResolver('id')]
        MeetId $id,
        #[MapRequestPayload]
        UpdateMeetInputDto $inputDto,
    ): JsonResponse {
        $this->commandBus->dispatch(new UpdateMeetCommand(
            id: $id,
            title: $inputDto->title,
            description: $inputDto->description,
            location: $inputDto->location,
            date: DateTimeImmutable::fromString($inputDto->date),
            category: Category::from($inputDto->category),
            maxGuests: $inputDto->maxGuests,
        ));

        return new JsonResponse();
    }
}
