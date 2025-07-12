<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Command\UpdateUser\UpdateUserCommand;
use App\Application\CommandBusInterface;
use App\Application\DTO\UpdateUserInputDto;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\UserId;
use App\Domain\ValueObject\Sex;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

final readonly class UpdateUserController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(
        #[ValueResolver('id')]
        UserId $id,
        #[MapRequestPayload]
        UpdateUserInputDto $inputDto
    ): JsonResponse {
        $this->commandBus->dispatch(new UpdateUserCommand(
            id: $id,
            sex: Sex::from($inputDto->sex),
            firstname: $inputDto->firstname,
            lastname: $inputDto->lastname,
            bio: $inputDto->bio,
            birthday: DateTimeImmutable::fromString($inputDto->birthday),
            updatedAt: DateTimeImmutable::create(),
            city: $inputDto->city,
        ));

        return new JsonResponse();
    }
}
