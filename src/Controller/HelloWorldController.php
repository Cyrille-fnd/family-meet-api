<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class HelloWorldController extends AbstractController
{
    #[Route('/helloworld', name: 'helloworld', methods: ['GET'])]
    public function hello(): JsonResponse
    {
        return new JsonResponse('Hello world !');
    }
}
