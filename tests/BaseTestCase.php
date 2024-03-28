<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class BaseTestCase extends ApiTestCase
{
    protected JWTTokenManagerInterface $jwtTokenManager;

    protected EntityManagerInterface $entityManager;

    protected Client $client;

    protected Generator $faker;

    protected function setUp(): void
    {
        /** @var JWTTokenManagerInterface $jwtTokenManager */
        $jwtTokenManager = static::getContainer()->get('lexik_jwt_authentication.jwt_manager');
        $this->jwtTokenManager = $jwtTokenManager;

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->entityManager = $entityManager;

        $client = static::createClient();
        $this->client = $client;

        $this->faker = Factory::create();
    }

    public function authenticate(): string
    {
        $user = new User();
        $user
            ->setEmail('ferandc@gmail.com');

        return $this->jwtTokenManager->create($user);
    }
}
