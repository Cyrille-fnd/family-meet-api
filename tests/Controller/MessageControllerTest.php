<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageControllerTest extends ApiTestCase
{
    private JWTTokenManagerInterface $jwtTokenManager;

    protected function setUp(): void
    {
        /** @var JWTTokenManagerInterface $jwtTokenManager */
        $jwtTokenManager = static::getContainer()->get('lexik_jwt_authentication.jwt_manager');
        $this->jwtTokenManager = $jwtTokenManager;
    }

    public function testPostUserNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $body = [
            'content' => 'Ceci est un message de test',
        ];

        $client->request(
            Request::METHOD_POST,
            '/v1/api/users/user-gaston-id/chats/{chat}/messages',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],

                'body' => json_encode($body),
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testPostChatNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $body = [
            'content' => 'Ceci est un message de test',
        ];

        $client->request(
            Request::METHOD_POST,
            '/v1/api/users/user-cyrille-id/chats/chat-random-id/messages',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],

                'body' => json_encode($body),
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testPostSuccessful(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $body = [
            'content' => 'Ceci est un message de test',
        ];

        $response = $client->request(
            Request::METHOD_POST,
            '/v1/api/users/user-cyrille-id/chats/chat-jeux-id/messages',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],

                'body' => json_encode($body),
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArraySubset(
            [
                'content' => 'Ceci est un message de test',
            ],
            $content,
        );
    }
}
