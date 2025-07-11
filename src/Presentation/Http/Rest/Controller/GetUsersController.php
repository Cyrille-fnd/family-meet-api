<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Query\User\GetUsersQuery;
use App\Application\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

final readonly class GetUsersController
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function __invoke(
        #[MapQueryParameter]
        ?int $page,
        #[MapQueryParameter]
        ?int $limit,
    ): JsonResponse {
        $response = $this->queryBus->ask(new GetUsersQuery($page ?? 1, $limit ?? 10));

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
