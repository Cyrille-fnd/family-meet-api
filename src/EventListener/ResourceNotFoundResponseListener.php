<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener]
final class ResourceNotFoundResponseListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof NotFoundHttpException) {
            return;
        }

        $response = new JsonResponse([
            'code' => 'resource_not_found',
            'message' => $exception->getMessage(),
        ],
            Response::HTTP_NOT_FOUND,
        );

        $event->setResponse($response);
    }
}
