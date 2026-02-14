<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use App\Application\Query\User\GetUser\GetUserQuery;
use App\Application\QueryBusInterface;
use App\Infrastructure\Bridge\Symfony\Security\SecurityUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class GetMeController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Security $security,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        /** @var SecurityUser $securityUser */
        $securityUser = $this->security->getUser();
        $userId = $securityUser->getUser()->getId();

        $response = $this->queryBus->ask(new GetUserQuery(id: $userId));

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
