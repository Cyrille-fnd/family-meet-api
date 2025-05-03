<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Query\User\GetUserQuery;
use App\Application\QueryBusInterface;
use App\Domain\ValueObject\Identity\UserId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

final readonly class GetUserController
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function __invoke(
        #[ValueResolver('id')]
        UserId $id,
    ): JsonResponse {
        $response = $this->queryBus->ask(new GetUserQuery(id: $id));

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
