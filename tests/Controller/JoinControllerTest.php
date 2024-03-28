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

class JoinControllerTest extends BaseTestCase
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
    public function testJoinReturnsUser404(): void
    {
        $jwtToken = $this->authenticate();

        $host = $this->createUser();
        $meet = $this->createMeet($host);

        /** @var Uuid $meetId */
        $meetId = $meet->getId();
        $guestId = Uuid::v4();

        $response = $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/meets/%s/users/%s/join', $meetId->toRfc4122(), $guestId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(false), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertEquals('user_not_found', $content['code']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testJoinReturnsMeet404(): void
    {
        $jwtToken = $this->authenticate();

        $meetId = Uuid::v4();
        /** @var Uuid $guestId */
        $guestId = $this->createUser()->getId();

        $response = $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/meets/%s/users/%s/join', $meetId->toRfc4122(), $guestId->toRfc4122()),
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
    public function testJoinReturn200(): void
    {
        $jwtToken = $this->authenticate();

        $host = $this->createUser();
        $meet = $this->createMeet($host);

        /** @var Uuid $meetId */
        $meetId = $meet->getId();
        /** @var Uuid $guestId */
        $guestId = $this->createUser()->getId();

        $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/meets/%s/users/%s/join', $meetId->toRfc4122(), $guestId->toRfc4122()),
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
    public function testUnjoinReturnsUser404(): void
    {
        $jwtToken = $this->authenticate();

        $host = $this->createUser();
        $meet = $this->createMeet($host);

        /** @var Uuid $meetId */
        $meetId = $meet->getId();
        $guestId = Uuid::v4();

        $response = $this->client->request(
            Request::METHOD_DELETE,
            sprintf('/api/v2/meets/%s/users/%s/unjoin', $meetId, $guestId),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(false), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertEquals('user_not_found', $content['code']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testUnjoinReturnsMeet404(): void
    {
        $jwtToken = $this->authenticate();

        $meetId = Uuid::v4();
        /** @var Uuid $guestId */
        $guestId = $this->createUser()->getId();

        $response = $this->client->request(
            Request::METHOD_DELETE,
            sprintf('/api/v2/meets/%s/users/%s/unjoin', $meetId->toRfc4122(), $guestId->toRfc4122()),
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
    public function testUnjoinReturns200(): void
    {
        $jwtToken = $this->authenticate();

        $host = $this->createUser();
        $meet = $this->createMeet($host);

        /** @var Uuid $meetId */
        $meetId = $meet->getId();
        /** @var Uuid $guestId */
        $guestId = $this->createUser()->getId();

        $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/meets/%s/users/%s/join', $meetId->toRfc4122(), $guestId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    private function createMeet(User $user): Meet
    {
        $chat = $this->createChat();
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

    private function createChat(): Chat
    {
        $chat = new Chat();
        $this->entityManager->persist($chat);
        $this->entityManager->flush();

        return $chat;
    }
}
