<?php

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use App\Service\ElasticSearch\ElasticSearchLocalClientGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    private ElasticSearchLocalClientGenerator $clientGenerator;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
        ElasticSearchLocalClientGenerator $clientGenerator
    ) {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->clientGenerator = $clientGenerator;
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
            $client->indices()->create([
                'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            ]);

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
            var_dump($_ENV['ELASTICSEARCH_HOST']);
            var_dump($_ENV['ELASTICSEARCH_PORT']);
            var_dump($exception->getMessage());
        }

        $chatRaclette = new Chat();
        $chatRaclette
            ->setId('chat-raclette-id')
            // ->setEvent($eventRaclette)
            ->setCreatedAt(new \DateTime());
        /*foreach ($eventRaclette->getGuests() as $guest) {
            $chatRaclette->addChatter($guest);
        }*/
        $manager->persist($chatRaclette);

        $chatFive = new Chat();
        $chatFive
            ->setId('chat-five-id')
            // ->setEvent($eventFive)
            ->setCreatedAt(new \DateTime());
        /*foreach ($eventFive->getGuests() as $guest) {
            $chatFive->addChatter($guest);
        }*/
        $manager->persist($chatFive);

        $chatJeux = new Chat();
        $chatJeux
            ->setId('chat-jeux-id')
            // ->setEvent($eventJeux)
            ->setCreatedAt(new \DateTime());
        /*foreach ($eventJeux->getGuests() as $guest) {
            $chatJeux->addChatter($guest);
        }*/
        $manager->persist($chatJeux);

        $chatClub = new Chat();
        $chatClub
            ->setId('chat-club-id')
            // ->setEvent($eventClub)
            ->setCreatedAt(new \DateTime());
        /*foreach ($eventClub->getGuests() as $guest) {
            $chatClub->addChatter($guest);
        }*/
        $manager->persist($chatClub);

        $messageRaclette = new Message();
        $messageRaclette
            ->setId('message-raclette-1-id')
            ->setAuthor($userDimitri)
            ->setContent('Trop hate de me régaler !!')
            ->setCreatedAt(new \DateTime())
            ->setChat($chatRaclette);
        $manager->persist($messageRaclette);

        $manager->flush();
    }
}
