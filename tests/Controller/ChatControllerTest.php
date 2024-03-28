<?php

namespace App\Tests\Controller;

use App\Entity\Chat;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ChatControllerTest extends BaseTestCase
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
        $jwtToken = $this->authenticate();

        $response = $this->client->request(
            Request::METHOD_POST,
            '/api/v2/chats',
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
                ],
            ],
        );

        /** @var array<string, string> $content */
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArrayHasKey('id', $content);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testGetReturns404(): void
    {
        $jwtToken = $this->authenticate();

        $this->client->request(
            Request::METHOD_GET,
            '/v1/api/chats/chat-random-id',
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

        /** @var Uuid $chatId */
        $chatId = $this->createChat()->getId();

        $response = $this->client->request(
            Request::METHOD_GET,
            '/api/v2/chats/'.$chatId->toRfc4122(),
            [
                'headers' => [
                    'Authorization' => 'bearer '.$jwtToken,
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

    private function createChat(): Chat
    {
        $chat = new Chat();
        $this->entityManager->persist($chat);
        $this->entityManager->flush();

        return $chat;
    }
}
