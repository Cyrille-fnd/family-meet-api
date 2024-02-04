<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends ApiTestCase
{
    private JWTTokenManagerInterface $jwtTokenManager;

    protected function setUp(): void
    {
        /** @var JWTTokenManagerInterface $jwtTokenManager */
        $jwtTokenManager = static::getContainer()->get('lexik_jwt_authentication.jwt_manager');
        $this->jwtTokenManager = $jwtTokenManager;
    }

    public function testPostSuccessful(): void
    {
        $client = static::createClient();

        $body = [
            'email' => 'toto@gmail.com',
            'sex' => 'male',
            'password' => 'password',
            'firstname' => 'Daniel',
            'lastname' => 'Craig',
            'bio' => 'For Vesper Lynd',
            'birthday' => '1962-12-01 00:00:00',
            'city' => 'London',
            'pictureUrl' => null,
        ];

        $response = $client->request(
            Request::METHOD_POST,
            '/v1/api/register',
            [
                'body' => json_encode($body),
            ]
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('token', $content);
        $this->assertArrayHasKey('createdAt', $content);
        unset($body['password']);
        $this->assertArraySubset($body, $content);
    }

    public function testPostFailUserAlreadyExist(): void
    {
        $client = static::createClient();

        $body = [
            'email' => 'ferandc@gmail.com',
            'sex' => 'male',
            'password' => 'password',
            'firstname' => 'Cyrille',
            'lastname' => 'Ferand',
            'bio' => 'Ici pour le rhum',
            'birthday' => '1989-12-01 00:00:00',
            'city' => 'Paris',
            'pictureUrl' => null,
        ];

        $client->request(
            Request::METHOD_POST,
            '/v1/api/register',
            [
                'body' => json_encode($body),
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonContains(['code' => 'cannot_create_user', 'message' => 'cannot create user']);
    }

    public function testGetUnauthorized(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/v1/api/users/user-cyrille-id');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertJsonContains(['code' => Response::HTTP_UNAUTHORIZED, 'message' => 'JWT Token not found']);
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
            '/v1/api/users/user-gaston-id',
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
            '/v1/api/users/user-cyrille-id',
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
            'id' => 'user-cyrille-id',
            'email' => 'ferandc@gmail.com',
            'firstname' => 'Cyrille',
            'lastname' => 'Ferand',
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
            'sex' => 'male',
            'firstname' => 'Cyrille',
            'lastname' => 'Ferand',
            'bio' => 'Ici pour le rhum',
            'birthday' => '1989-12-01 00:00:00',
            'city' => 'Paris',
            'pictureUrl' => null,
        ];

        $client = static::createClient();

        $client->request(
            Request::METHOD_PUT,
            '/v1/api/users/user-gaston-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
                'body' => json_encode($body),
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
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
            'sex' => 'male',
            'firstname' => 'Cyrille Gaston',
            'lastname' => 'Ferand',
            'bio' => 'bio modifiée',
            'birthday' => '1989-12-01 00:00:00',
            'city' => 'Paris',
            'pictureUrl' => null,
        ];

        $response = $client->request(
            Request::METHOD_PUT,
            '/v1/api/users/user-cyrille-id',
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
            'id' => 'user-cyrille-id',
            'bio' => 'bio modifiée',
            'email' => 'ferandc@gmail.com',
            'firstname' => 'Cyrille Gaston',
            'lastname' => 'Ferand',
        ], $content);
    }

    public function testDeleteNotFound(): void
    {
        $user = new User();
        $user
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com');

        $token = $this->jwtTokenManager->create($user);

        $client = static::createClient();

        $client->request(
            Request::METHOD_DELETE,
            '/v1/api/users/user-gaston-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
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
            '/v1/api/users/user-cyrille-id',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
