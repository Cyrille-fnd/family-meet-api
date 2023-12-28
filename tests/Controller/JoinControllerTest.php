<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JoinControllerTest extends ApiTestCase
{
    private JWTTokenManagerInterface $jwtTokenManager;

    protected function setUp(): void
    {
        /** @var JWTTokenManagerInterface $jwtTokenManager */
        $jwtTokenManager = static::getContainer()->get('lexik_jwt_authentication.jwt_manager');
        $this->jwtTokenManager = $jwtTokenManager;
    }

    public function testJoinUserNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_POST,
            '/v1/api/users/user-gaston-id/events/event-raclette-id/join',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testJoinEventNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_POST,
            '/v1/api/users/user-cyrille-id/events/event-foot-id/join',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testJoinSuccessful(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_POST,
            '/v1/api/users/user-cyrille-id/events/event-raclette-id/join',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testUnjoinUserNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_DELETE,
            '/v1/api/users/user-gaston-id/events/event-raclette-id/unjoin',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testUnjoinEventNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_DELETE,
            '/v1/api/users/user-cyrille-id/events/event-foot-id/unjoin',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testUnjoinSuccessful(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_DELETE,
            '/v1/api/users/user-cyrille-id/events/event-raclette-id/unjoin',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
