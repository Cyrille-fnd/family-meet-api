<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Query\Meet\GetMeet\GetMeetQuery;
use App\Application\QueryBusInterface;
use App\Domain\ValueObject\Identity\MeetId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

final readonly class GetMeetController
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function __invoke(
        #[ValueResolver('id')]
        MeetId $id,
    ): JsonResponse {
        $response = $this->queryBus->ask(new GetMeetQuery(id: $id));

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
