<?php

declare(strict_types=1);

namespace App\Meet\Presentation\Http\Rest\Controller;

use App\Meet\Application\CommandBusInterface;
use App\Meet\Application\DTO\SignupInputDto;
use App\Meet\Application\Signup\SignupCommand;
use App\Meet\Domain\ValueObject\SignupInformation;
use App\Meet\Domain\ValueObject\UserId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

final readonly class SignupController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(
        #[MapRequestPayload] SignupInputDto $signupDto,
    ): JsonResponse {
        $signupInformation = SignupInformation::create(
            id: UserId::create(),
            email: $signupDto->email,
            password: $signupDto->password,
            sex: $signupDto->sex,
            firstName: $signupDto->firstname,
            lastName: $signupDto->lastname,
            bio: $signupDto->bio,
            birthday: $signupDto->birthday,
            city: $signupDto->city,
            pictureUrl: $signupDto->pictureUrl,
        );

        $this->commandBus->dispatch(new SignupCommand(signupInformation: $signupInformation));

        return new JsonResponse(['id' => $signupInformation->id->value()], Response::HTTP_CREATED);
    }
}
