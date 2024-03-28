<?php

namespace App\Tests\Controller;

use App\Entity\Chat;
use App\Entity\Meet;
use App\Entity\User;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MeetControllerTest extends BaseTestCase
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testPostReturnsUser404(): void
    {
        $jwtToken = $this->authenticate();

        $body = [
            'title' => 'Diner au bureau',
            'location' => '2 rue Condorcet, 94800 Villejuif',
            'date' => '2023-06-06 20:00:00',
            'category' => 'restaurant',
            'participantMax' => 10,
        ];

        $userId = Uuid::v4();

        $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/users/%s/meets', $userId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
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
     */
    public function testPostReturn201(): void
    {
        $jwtToken = $this->authenticate();

        $body = [
            'title' => 'Diner au bureau',
            'location' => '2 rue Condorcet, 94800 Villejuif',
            'description' => 'Ceci est une description',
            'date' => '2023-06-06 20:00:00',
            'category' => 'restaurant',
            'participantMax' => 10,
        ];

        /** @var Uuid $userId */
        $userId = $this->createUser()->getId();

        $response = $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/users/%s/meets', $userId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],

                'body' => json_encode($body),
            ],
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
    public function testGetReturnsUser404(): void
    {
        $jwtToken = $this->authenticate();

        $unknownMeetId = Uuid::v4();

        $response = $this->client->request(
            Request::METHOD_GET,
            sprintf('/api/v2/meets/%s', $unknownMeetId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(false), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertEquals('meet_not_found', $content['code']);
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

        /** @var Uuid $meetId */
        $meetId = $this->createMeet()->getId();

        $response = $this->client->request(
            Request::METHOD_GET,
            sprintf('/api/v2/meets/%s', $meetId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals($meetId->toRfc4122(), $content['id']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testPutReturns404(): void
    {
        $jwtToken = $this->authenticate();

        $unknownMeetId = Uuid::v4();

        $body = [
            'title' => 'Diner au bureau',
            'description' => 'Ceci est une description',
            'location' => '2 rue Condorcet, 94800 Villejuif',
            'date' => '2023-06-06 20:00:00',
            'category' => 'restaurant',
            'participantMax' => 10,
        ];

        $response = $this->client->request(
            Request::METHOD_PUT,
            sprintf('/api/v2/meets/%s', $unknownMeetId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
                'body' => json_encode($body),
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(false), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertEquals('meet_not_found', $content['code']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testPutReturns200(): void
    {
        $jwtToken = $this->authenticate();

        $meet = $this->createMeet();

        $body = [
            'title' => $meet->getTitle(),
            'description' => $meet->getDescription(),
            'location' => $meet->getLocation(),
            'date' => $meet->getDate()->format('Y-m-d h:i:s'),
            'category' => $meet->getCategory(),
            'participantMax' => $meet->getMaxGuests(),
        ];

        /** @var Uuid $meetId */
        $meetId = $meet->getId();

        $response = $this->client->request(
            Request::METHOD_PUT,
            sprintf('/api/v2/meets/%s', $meetId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
                'body' => json_encode($body),
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(false), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals($meet->getTitle(), $content['title']);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testGetAllReturns200(): void
    {
        $jwtToken = $this->authenticate();

        $this->client->request(
            Request::METHOD_GET,
            '/api/v2/meets',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testDeleteReturns404(): void
    {
        $jwtToken = $this->authenticate();

        $unknownMeetId = Uuid::v4();

        $response = $this->client->request(
            Request::METHOD_DELETE,
            sprintf('/api/v2/meets/%s', $unknownMeetId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(false), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertEquals('meet_not_found', $content['code']);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteSuccessful(): void
    {
        $jwtToken = $this->authenticate();

        /** @var Uuid $meetId */
        $meetId = $this->createMeet()->getId();

        $this->client->request(
            Request::METHOD_DELETE,
            sprintf('/api/v2/meets/%s', $meetId->toRfc4122()),
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

    private function createMeet(): Meet
    {
        $chat = $this->createChat();
        $user = $this->createUser();
        /** @var string $category */
        $category = $this->faker->randomElement(['restaurant', 'jeux']);
        $meet = new Meet();
        $meet
            ->setTitle($this->faker->sentence(8))
            ->setLocation($this->faker->address())
            ->setDate(new \DateTime())
            ->setCategory($category)
            ->setMaxGuests(10)
            ->setChat($chat)
            ->setHost($user);
        $this->entityManager->persist($meet);
        $this->entityManager->flush();

        return $meet;
    }

    private function createChat(): Chat
    {
        $chat = new Chat();
        $this->entityManager->persist($chat);
        $this->entityManager->flush();

        return $chat;
    }
}
