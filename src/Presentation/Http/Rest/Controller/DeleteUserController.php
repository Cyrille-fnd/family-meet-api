<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Command\DeleteUser\DeleteUserCommand;
use App\Application\CommandBusInterface;
use App\Domain\ValueObject\Identity\UserId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

final readonly class DeleteUserController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(
        #[ValueResolver('id')]
        UserId $id
    ): JsonResponse {
        $this->commandBus->dispatch(new DeleteUserCommand(id: $id));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
