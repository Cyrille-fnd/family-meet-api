<?php

declare(strict_types=1);

namespace App\Presentation\Http\Rest\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class HealthcheckController
{
    public function __invoke(
    ): JsonResponse {
        return new JsonResponse([
            'data' => 'System seems healthy!',
        ]);
    }
}
