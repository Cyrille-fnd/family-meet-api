<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatControllerTest extends ApiTestCase
{
    private JWTTokenManagerInterface $jwtTokenManager;

    protected function setUp(): void
    {
        /** @var JWTTokenManagerInterface $jwtTokenManager */
        $jwtTokenManager = static::getContainer()->get('lexik_jwt_authentication.jwt_manager');
        $this->jwtTokenManager = $jwtTokenManager;
    }

    public function testPost(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $response = $client->request(
            Request::METHOD_POST,
            '/v1/api/chats',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('chatters', $content);
        $this->assertArrayHasKey('messages', $content);
        $this->assertArrayHasKey('createdAt', $content);
    }

    public function testGetChatNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_GET,
            '/v1/api/chats/chat-random-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetSuccessful(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $response = $client->request(
            Request::METHOD_GET,
            '/v1/api/chats/chat-raclette-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('chatters', $content);
        $this->assertArrayHasKey('messages', $content);
        $this->assertArrayHasKey('createdAt', $content);
    }
}
