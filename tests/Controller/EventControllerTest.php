<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventControllerTest extends ApiTestCase
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
            'title' => 'Diner au bureau',
            'location' => '2 rue Condorcet, 94800 Villejuif',
            'date' => '2023-06-06 20:00:00',
            'category' => 'restaurant',
            'participantMax' => 10,
        ];

        $client->request(
            Request::METHOD_POST,
            '/v1/api/events?hostId=user-gaston-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],

                'body' => json_encode($body),
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testPostSuccesful(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $body = [
            'title' => 'Diner au bureau',
            'location' => '2 rue Condorcet, 94800 Villejuif',
            'description' => 'Ceci est une description',
            'date' => '2023-06-06 20:00:00',
            'category' => 'restaurant',
            'participantMax' => 10,
        ];

        $response = $client->request(
            Request::METHOD_POST,
            '/v1/api/events?hostId=user-cyrille-id',
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
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('createdAt', $content);
        $this->assertArraySubset($body, $content);
    }

    public function testGetNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_GET,
            '/v1/api/events/event-foot-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST
        );
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
            '/v1/api/events/event-raclette-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertArraySubset([
            'title' => 'Raclette chez Cyrille',
            'location' => '2 rue Condorcet, 94800 Villejuif',
            'date' => '2024-01-09 20:00:00',
            'participantMax' => 6,
        ], $content);
    }

    public function testPutNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $body = [
            'title' => 'Diner au bureau',
            'description' => 'Ceci est une description',
            'location' => '2 rue Condorcet, 94800 Villejuif',
            'date' => '2023-06-06 20:00:00',
            'category' => 'restaurant',
            'participantMax' => 10,
        ];

        $client = static::createClient();

        $client->request(
            Request::METHOD_PUT,
            '/v1/api/events/event-foot-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
                'body' => json_encode($body),
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testPutSuccessful(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $body = [
            'title' => 'Raclette chez Cyrille',
            'description' => 'Ceci est une description',
            'location' => '2 rue Condorcet, 94800 Villejuif',
            'date' => '2023-06-06 20:00:00',
            'category' => 'restaurant',
            'participantMax' => 10,
        ];

        $response = $client->request(
            Request::METHOD_PUT,
            '/v1/api/events/event-jeux-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
                'body' => json_encode($body),
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertArraySubset([
            'title' => 'Raclette chez Cyrille',
            'description' => 'Ceci est une description',
            'location' => '2 rue Condorcet, 94800 Villejuif',
            'date' => '2023-06-06 20:00:00',
            'category' => 'restaurant',
            'participantMax' => 10,
        ], $content);
    }

    public function testGetAll(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_GET,
            '/v1/api/events',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDeleteSuccessful(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_DELETE,
            '/v1/api/events/event-club-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
