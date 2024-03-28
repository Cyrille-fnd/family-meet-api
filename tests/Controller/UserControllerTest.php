<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserControllerTest extends BaseTestCase
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testPostReturns201(): void
    {
        $body = [
            'email' => $this->faker->email(),
            'sex' => 'male',
            'password' => 'password',
            'firstname' => 'Daniel',
            'lastname' => 'Craig',
            'bio' => 'For Vesper Lynd',
            'birthday' => '1962-12-01 00:00:00',
            'city' => 'London',
            'pictureUrl' => null,
        ];

        $response = $this->client->request(
            Request::METHOD_POST,
            '/api/v2/users',
            [
                'body' => json_encode($body),
            ]
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArrayHasKey('id', $content);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testPostReturns400(): void
    {
        $user = $this->createUser();

        $body = [
            'email' => $user->getEmail(),
            'sex' => 'male',
            'password' => 'password',
            'firstname' => 'Cyrille',
            'lastname' => 'Ferand',
            'bio' => 'Ici pour le rhum',
            'birthday' => '1989-12-01 00:00:00',
            'city' => 'Paris',
            'pictureUrl' => null,
        ];

        $response = $this->client->request(
            Request::METHOD_POST,
            '/api/v2/users',
            [
                'body' => json_encode($body),
            ]
        );

        /** @var array<string,string> $content */
        $content = json_decode($response->getContent(false), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals('user_already_exists', $content['code']);
    }

    public function testGetReturns400(): void
    {
        $jwtToken = $this->authenticate();

        $unknownUserId = Uuid::v4();

        $this->client->request(
            Request::METHOD_GET,
            sprintf('/api/v2/users/%s', $unknownUserId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetReturns200(): void
    {
        $jwtToken = $this->authenticate();

        /** @var Uuid $userId */
        $userId = $this->createUser()->getId();

        $response = $this->client->request(
            Request::METHOD_GET,
            sprintf('/api/v2/users/%s', $userId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertArraySubset([
            'id' => $userId->toRfc4122(),
        ], $content);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testPutReturn404(): void
    {
        $token = $this->authenticate();

        $unknownUserId = Uuid::v4();

        $body = [
            'sex' => 'male',
            'firstname' => 'Cyrille',
            'lastname' => 'Ferand',
            'bio' => 'Ici pour le rhum',
            'birthday' => '1989-12-01 00:00:00',
            'city' => 'Paris',
            'pictureUrl' => null,
        ];

        $this->client->request(
            Request::METHOD_PUT,
            sprintf('/api/v2/users/%s', $unknownUserId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$token,
                ],
                'body' => json_encode($body),
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     */
    public function testPutReturns200(): void
    {
        $jwtToken = $this->authenticate();

        $body = [
            'sex' => 'male',
            'firstname' => 'Cyrille Gaston',
            'lastname' => 'Ferand',
            'bio' => 'bio modifiée',
            'birthday' => '1989-12-01 00:00:00',
            'city' => 'Paris',
            'pictureUrl' => null,
        ];

        /** @var Uuid $userId */
        $userId = $this->createUser()->getId();

        $response = $this->client->request(
            Request::METHOD_PUT,
            sprintf('/api/v2/users/%s', $userId),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
                'body' => json_encode($body),
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArraySubset([
            'id' => $userId->toRfc4122(),
        ], $content);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteReturns404(): void
    {
        $jwtToken = $this->authenticate();
        $userId = Uuid::v4();

        $this->client->request(
            Request::METHOD_DELETE,
            sprintf('/api/v2/users/%s', $userId),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteReturn204(): void
    {
        $jwtToken = $this->authenticate();

        /** @var Uuid $userId */
        $userId = $this->createUser()->getId();

        $client = static::createClient();

        $client->request(
            Request::METHOD_DELETE,
            sprintf('/api/v2/users/%s', $userId),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function createUser(): User
    {
        /** @var string $sex */
        $sex = $this->faker->randomElement(['male', 'female']);
        $user = new User();
        $user
            ->setEmail($this->faker->email())
            ->setPassword($this->faker->password())
            ->setSex($sex)
            ->setFirstname($this->faker->firstName())
            ->setLastname($this->faker->lastName())
            ->setBirthday(new \DateTime())
            ->setCity($this->faker->city());
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
