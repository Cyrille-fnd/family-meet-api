<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Command\Register\RegisterCommand;
use App\Application\CommandBusInterface;
use App\Application\DTO\RegisterInputDto;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\UserId;
use App\Domain\ValueObject\RegisterInformation;
use App\Domain\ValueObject\Sex;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

final readonly class RegisterController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(
        #[MapRequestPayload]
        RegisterInputDto $registerInputDto,
    ): JsonResponse {
        $registerInformation = RegisterInformation::create(
            id: UserId::create(),
            email: $registerInputDto->email,
            password: $registerInputDto->password,
            sex: Sex::from($registerInputDto->sex),
            firstname: $registerInputDto->firstname,
            lastname: $registerInputDto->lastname,
            bio: $registerInputDto->bio,
            birthday: DateTimeImmutable::fromString($registerInputDto->birthday),
            createdAt: DateTimeImmutable::create(),
            updatedAt: DateTimeImmutable::create(),
            city: $registerInputDto->city,
            pictureUrl: $registerInputDto->pictureUrl,
        );

        $this->commandBus->dispatch(new RegisterCommand(registerInformation: $registerInformation));

        return new JsonResponse(['id' => $registerInformation->id->value()], Response::HTTP_CREATED);
    }
}
