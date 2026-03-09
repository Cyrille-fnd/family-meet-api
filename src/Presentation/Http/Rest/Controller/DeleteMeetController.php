<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Command\DeleteMeet\DeleteMeetCommand;
use App\Application\CommandBusInterface;
use App\Domain\ValueObject\Identity\MeetId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

final readonly class DeleteMeetController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(
        #[ValueResolver('id')]
        MeetId $id,
    ): JsonResponse {
        $this->commandBus->dispatch(new DeleteMeetCommand(id: $id));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
