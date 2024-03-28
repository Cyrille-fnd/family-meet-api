<?php

namespace App\Tests\Controller;

use App\Entity\Chat;
use App\Entity\User;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MessageControllerTest extends BaseTestCase
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
    public function testPostReturnsChat404(): void
    {
        $jwtToken = $this->authenticate();

        $body = [
            'content' => 'Ceci est un message de test',
        ];

        $unknownChatId = Uuid::v4();
        /** @var Uuid $userId */
        $userId = $this->createChatWithoutChatter()->getId();

        $response = $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/chats/%s/users/%s/messages', $unknownChatId->toRfc4122(), $userId->toRfc4122()),
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
        $this->assertEquals('chat_not_found', $content['code']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testPostReturnsUser404(): void
    {
        $jwtToken = $this->authenticate();

        $body = [
            'content' => 'Ceci est un message de test',
        ];

        /** @var Uuid $chatId */
        $chatId = $this->createChatWithoutChatter()->getId();
        $unknownUserId = Uuid::v4();

        $response = $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/chats/%s/users/%s/messages', $chatId->toRfc4122(), $unknownUserId->toRfc4122()),
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
        $this->assertEquals('user_not_found', $content['code']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testPostReturns400(): void
    {
        $jwtToken = $this->authenticate();

        $body = [
            'content' => 'Ceci est un message de test',
        ];

        /** @var Uuid $chatId */
        $chatId = $this->createChatWithoutChatter()->getId();
        /** @var Uuid $userId */
        $userId = $this->createUser()->getId();

        $response = $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/chats/%s/users/%s/messages', $chatId->toRfc4122(), $userId->toRfc4122()),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],

                'body' => json_encode($body),
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(false), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals('user_not_chat_member', $content['code']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     */
    public function testPostReturn201(): void
    {
        $token = $this->authenticate();

        $body = [
            'content' => 'Ceci est un message de test',
        ];

        $user = $this->createUser();
        $chat = $this->createChatWithChatter($user);

        /** @var Uuid $chatId */
        $chatId = $chat->getId();
        /** @var Uuid $userId */
        $userId = $user->getId();

        $response = $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/v2/chats/%s/users/%s/messages', $chatId->toRfc4122(), $userId->toRfc4122()),
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

    private function createUser(): User
    {
        /** @var string $sex */
        $sex = $this->faker->randomElement(['male', 'female']);
        $user = new User();
        $user
            ->setEmail($this->faker->email())
            ->setPassword($this->faker->password())
            ->setFirstname($this->faker->firstName())
            ->setLastname($this->faker->lastName())
            ->setSex($sex)
            ->setBirthday(new \DateTime())
            ->setCity($this->faker->city());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function createChatWithoutChatter(): Chat
    {
        $chat = new Chat();
        $this->entityManager->persist($chat);
        $this->entityManager->flush();

        return $chat;
    }

    private function createChatWithChatter(User $user): Chat
    {
        $chat = $this->createChatWithoutChatter();
        $chat->addChatter($user);
        $this->entityManager->flush();

        return $chat;
    }
}
