<?php

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use App\Service\ElasticSearch\ElasticSearchLocalClientGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    private ElasticSearchLocalClientGenerator $clientGenerator;

    private Generator $faker;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
        ElasticSearchLocalClientGenerator $clientGenerator
    ) {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->clientGenerator = $clientGenerator;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $userCyrille = new User();
        $userCyrille
            ->setId('user-cyrille-id')
            ->setEmail('ferandc@gmail.com')
            ->setPassword($this->userPasswordHasher->hashPassword($userCyrille, 'password'))
            ->setRoles(['ROLE_USER'])
            ->setSex('male')
            ->setFirstname('Cyrille')
            ->setLastname('Ferand')
            ->setBio('Ici pour le fun !!')
            ->setBirthday(new \DateTime('1989-02-06'))
            ->setCity('Villejuif')
            ->setPictureUrl('https://media.licdn.com/dms/image/C4D03AQFwsiU89fQuHg/profile-displayphoto-shrink_800_800/0/1610137674745?e=1707955200&v=beta&t=4-_8BYbCE6J4wIm8pdpPHJQN74thveWfwwMzQDqWIQc')
            ->setCreatedAt(new \DateTime());
        $manager->persist($userCyrille);

        $userMelinda = new User();
        $userMelinda
            ->setId('user-melinda-id')
            ->setEmail('apatoutm@gmail.com')
            ->setPassword($this->userPasswordHasher->hashPassword($userMelinda, 'password'))
            ->setRoles(['ROLE_USER'])
            ->setSex('female')
            ->setFirstname('Melinda')
            ->setLastname('Apatout')
            ->setBio('Ici par curiosité')
            ->setBirthday(new \DateTime('2000-08-20'))
            ->setCity('Orly')
            ->setPictureUrl(null)
            ->setCreatedAt(new \DateTime());
        $manager->persist($userMelinda);

        $userGeoffrey = new User();
        $userGeoffrey
            ->setId('user-geoffrey-id')
            ->setEmail('apatoutg@gmail.com')
            ->setPassword($this->userPasswordHasher->hashPassword($userGeoffrey, 'password'))
            ->setRoles(['ROLE_USER'])
            ->setSex('male')
            ->setFirstname('Geoffrey')
            ->setLastname('Apatout')
            ->setBio(null)
            ->setBirthday(new \DateTime('1986-07-02'))
            ->setCity('Orly')
            ->setPictureUrl(null)
            ->setCreatedAt(new \DateTime());
        $manager->persist($userGeoffrey);

        $userDimitri = new User();
        $userDimitri
            ->setId('user-dimitri-id')
            ->setEmail('niced@gmail.com')
            ->setPassword($this->userPasswordHasher->hashPassword($userDimitri, 'password'))
            ->setRoles(['ROLE_USER'])
            ->setSex('male')
            ->setFirstname('Dimitri')
            ->setLastname('Nice')
            ->setBio(null)
            ->setBirthday(new \DateTime('1987-11-26'))
            ->setCity('Thiais')
            ->setPictureUrl(null)
            ->setCreatedAt(new \DateTime());
        $manager->persist($userDimitri);

        $userIngrid = new User();
        $userIngrid
            ->setId('user-ingrid-id')
            ->setEmail('apatouti@gmail.com')
            ->setPassword($this->userPasswordHasher->hashPassword($userIngrid, 'password'))
            ->setRoles(['ROLE_USER'])
            ->setSex('female')
            ->setFirstname('Ingrid')
            ->setLastname('Apatout')
            ->setBio(null)
            ->setBirthday(new \DateTime('1977-06-06'))
            ->setCity('Orly')
            ->setPictureUrl(null)
            ->setCreatedAt(new \DateTime());
        $manager->persist($userIngrid);

        $client = $this->clientGenerator->getClient();

        $eventRaclette = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'id' => 'event-raclette-id',
            'body' => [
                'title' => 'Raclette chez Cyrille',
                'location' => '2 rue Condorcet, 94800 Villejuif',
                'date' => '2024-01-09 20:00:00',
                'category' => 'restaurant',
                'participantMax' => 6,
                'createdAt' => '2024-01-09 20:00:00',
                'hostId' => 'user-cyrille-id',
                'guests' => [
                    'user-dimitri-id',
                    'user-geoffrey-id',
                    'user-melinda-id',
                ],
            ],
        ];

        $eventFive = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'id' => 'event-five-id',
            'body' => [
                'title' => 'Five à Créteil',
                'location' => '2 rue des poireaux, 94000 Créteil',
                'date' => '2024-01-09 20:00:00',
                'category' => 'restaurant',
                'participantMax' => 5,
                'createdAt' => '2024-01-09 20:00:00',
                'hostId' => 'user-dimitri-id',
                'guests' => [
                ],
            ],
        ];

        $eventJeux = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'id' => 'event-jeux-id',
            'body' => [
                'title' => 'Jeux de sociétés',
                'location' => '15 rue du Commerce, 94310 Orly',
                'date' => '2024-01-09 20:00:00',
                'category' => 'restaurant',
                'participantMax' => 4,
                'createdAt' => '2024-01-09 20:00:00',
                'hostId' => 'user-ingrid-id',
                'guests' => [
                    'user-melinda-id',
                ],
            ],
        ];

        $eventClub = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'id' => 'event-club-id',
            'body' => [
                'title' => 'Danser à la Favela',
                'location' => '15 rue du Faubourg du temple, 75010 Paris',
                'date' => '2024-01-09 20:00:00',
                'category' => 'restaurant',
                'participantMax' => 4,
                'createdAt' => '2024-01-09 20:00:00',
                'hostId' => 'user-cyrille-id',
                'guests' => [
                ],
            ],
        ];

        try {
            $indexParams = [
                'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            ];

            if (!$client->indices()->exists($indexParams)) {
                $client->indices()->create($indexParams);
            }

            $jsonQuery = '{"query": {"match_all": {}}}';

            $params = [
                'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
                'body' => $jsonQuery,
            ];

            $client->deleteByQuery($params);
            $client->create($eventRaclette);
            $client->create($eventFive);
            $client->create($eventJeux);
            $client->create($eventClub);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }

        $chatRaclette = new Chat();
        $chatRaclette
            ->setId('chat-raclette-id')
            ->setCreatedAt(new \DateTime());
        $manager->persist($chatRaclette);

        $chatFive = new Chat();
        $chatFive
            ->setId('chat-five-id')
            ->setCreatedAt(new \DateTime());
        $manager->persist($chatFive);

        $chatJeux = new Chat();
        $chatJeux
            ->setId('chat-jeux-id')
            ->setCreatedAt(new \DateTime());
        $manager->persist($chatJeux);

        $chatClub = new Chat();
        $chatClub
            ->setId('chat-club-id')
            ->setCreatedAt(new \DateTime());
        $manager->persist($chatClub);

        $messageRaclette = new Message();
        $messageRaclette
            ->setId('message-raclette-1-id')
            ->setAuthor($userDimitri)
            ->setContent('Trop hate de me régaler !!')
            ->setCreatedAt(new \DateTime())
            ->setChat($chatRaclette);
        $manager->persist($messageRaclette);

        $users = [];
        for ($i = 0; $i <= 20; ++$i) {
            $user = new User();
            /** @var string $sex */
            $sex = $this->faker->randomElement(['male, female']);
            $user
                ->setId($this->faker->uuid())
                ->setEmail($this->faker->email())
                ->setPassword($this->userPasswordHasher->hashPassword($user, $this->faker->password(10)))
                ->setRoles(['ROLE_USER'])
                ->setSex($sex)
                ->setFirstname($this->faker->firstName($sex))
                ->setLastname($this->faker->lastName())
                ->setBio($this->faker->sentence())
                ->setBirthday($this->faker->dateTimeBetween('-35 years'))
                ->setCity($this->faker->city())
                ->setPictureUrl('https://media.licdn.com/dms/image/C4D03AQFwsiU89fQuHg/profile-displayphoto-shrink_800_800/0/1610137674745?e=1707955200&v=beta&t=4-_8BYbCE6J4wIm8pdpPHJQN74thveWfwwMzQDqWIQc')
                ->setCreatedAt(new \DateTime());
            $manager->persist($user);

            $users[] = $user;
        }

        for ($i = 0; $i <= 100; ++$i) {
            /** @var User $host */
            $host = $this->faker->randomElement($users);

            $max = $this->faker->numberBetween(1, 20);

            /** @var User[] $guests */
            $guests = $this->faker->randomElements($users, $this->faker->numberBetween(1, $max));
            $guests = array_map(function (User $guest) {
                return $guest->getId();
            }, $guests);

            $event = [
                'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
                'id' => $this->faker->uuid(),
                'body' => [
                    'title' => $this->faker->sentence(7),
                    'location' => $this->faker->address(),
                    'date' => $this->faker->dateTimeThisYear()->format('Y-m-d h:i:s'),
                    'category' => $this->faker->randomElement(['restaurant, bar, travail, sport']),
                    'participantMax' => $max = $this->faker->numberBetween(1, 20),
                    'createdAt' => $this->faker->dateTimeBetween('-2 years', '-3 months')
                        ->format('y-m-d h:i:s'),
                    'hostId' => $host->getId(),
                    'guests' => $guests,
                ],
            ];

            try {
                $client->create($event);
            } catch (\Exception $exception) {
                var_dump($exception->getMessage());
            }
        }

        for ($i = 0; $i <= 15; ++$i) {
            $chatters = $this->faker->randomElements($users, null);

            $chat = new Chat();
            $chat
                ->setId($this->faker->uuid())
                ->setCreatedAt(new \DateTime());
            $manager->persist($chat);
            foreach ($chatters as $chatter) {
                $chat->addChatter($chatter);
            }

            for ($i = 0; $i <= $this->faker->randomNumber(); ++$i) {
                $message = new Message();
                /** @var User $user */
                $user = $this->faker->randomElement($users);
                $message
                    ->setId($this->faker->uuid())
                    ->setAuthor($user)
                    ->setContent($this->faker->sentence(7))
                    ->setCreatedAt(new \DateTime())
                    ->setChat($chat);
                $manager->persist($message);
            }
        }

        $manager->flush();
    }
}
