<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\Meet;
use App\Entity\Message;
use App\Entity\User;
use App\Meet\Domain\ValueObject\ChatId;
use App\Meet\Domain\ValueObject\MeetId;
use App\Meet\Domain\ValueObject\MessageId;
use App\Meet\Domain\ValueObject\UserId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    private Generator $faker;

    public function __construct(
        UserPasswordHasherInterface $userPasswordHasher,
    ) {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $userCyrille = User::create(
            id: UserId::create()->value(),
            email: 'ferandc@gmail.com',
            password: 'zz',
            sex: 'male',
            firstname: 'Cyrille',
            lastname: 'Ferand',
            bio: 'Ici pour le fun !!',
            birthday: new \DateTime('1989-02-06'),
            city: 'Villejuif',
            pictureUrl: 'https://media.licdn.com/dms/image/C4D03AQFwsiU89fQuHg/profile-displayphoto-shrink_800_800/0/1610137674745?e=1707955200&v=beta&t=4-_8BYbCE6J4wIm8pdpPHJQN74thveWfwwMzQDqWIQc'
        );
        $userCyrille->setPassword($this->userPasswordHasher->hashPassword($userCyrille, $userCyrille->getPassword()));
        $manager->persist($userCyrille);

        $userMelinda = User::create(
            id: UserId::create()->value(),
            email: 'apatoutm@gmail.com',
            password: 'zz',
            sex: 'female',
            firstname: 'Melinda',
            lastname: 'Apatout',
            bio: 'Ici par curiosité',
            birthday: new \DateTime('2000-08-20'),
            city: 'Orly',
            pictureUrl: null
        );
        $userMelinda->setPassword($this->userPasswordHasher->hashPassword($userMelinda, $userMelinda->getPassword()));
        $manager->persist($userMelinda);

        $userGeoffrey = User::create(
            id: UserId::create()->value(),
            email: 'apatoutg@gmail.com',
            password: 'zz',
            sex: 'male',
            firstname: 'Geoffrey',
            lastname: 'Apatout',
            bio: '',
            birthday: new \DateTime('1986-07-02'),
            city: 'Orly',
            pictureUrl: null
        );
        $userGeoffrey->setPassword($this->userPasswordHasher->hashPassword($userGeoffrey, $userGeoffrey->getPassword()));
        $manager->persist($userGeoffrey);

        $userDimitri = User::create(
            id: UserId::create()->value(),
            email: 'niced@gmail.com',
            password: 'zz',
            sex: 'male',
            firstname: 'Dimitri',
            lastname: 'Nice',
            bio: '',
            birthday: new \DateTime('1987-11-26'),
            city: 'Thiais',
            pictureUrl: null
        );
        $userDimitri->setPassword($this->userPasswordHasher->hashPassword($userDimitri, $userDimitri->getPassword()));
        $manager->persist($userDimitri);

        $userIngrid = User::create(
            id: UserId::create()->value(),
            email: 'apatouti@gmail.com',
            password: 'zz',
            sex: 'female',
            firstname: 'Ingrid',
            lastname: 'Apatout',
            bio: '',
            birthday: new \DateTime('1977-06-06'),
            city: 'Orly',
            pictureUrl: null
        );
        $userIngrid->setPassword($this->userPasswordHasher->hashPassword($userIngrid, $userIngrid->getPassword()));
        $manager->persist($userIngrid);

        $chatRaclette = Chat::create(ChatId::create()->value());
        $chatRaclette
            ->addChatter($userCyrille)
            ->addChatter($userMelinda)
            ->addChatter($userGeoffrey);
        $manager->persist($chatRaclette);

        $chatFive = Chat::create(ChatId::create()->value());
        $chatFive->addChatter($userDimitri);
        $manager->persist($chatFive);

        $chatJeux = Chat::create(ChatId::create()->value());
        $chatJeux
            ->addChatter($userIngrid)
            ->addChatter($userMelinda);
        $manager->persist($chatJeux);

        $chatClub = Chat::create(ChatId::create()->value());
        $chatClub
            ->addChatter($userMelinda);
        $manager->persist($chatClub);

        $meetRaclette = Meet::create(
            id: MeetId::create()->value(),
            title: 'Raclette chez Cyrille',
            description: $this->faker->sentence(50),
            location: 'Chez Cyrille',
            date: new \DateTime('2024-01-09 20:00:00'),
            category: 'Restaurant',
            maxGuests: 6,
            host: $userCyrille,
            chat: $chatRaclette,
        );

        $meetRaclette
            ->addGuest($userMelinda)
            ->addGuest($userGeoffrey);
        $manager->persist($meetRaclette);

        $meetFive = Meet::create(
            id: MeetId::create()->value(),
            title: 'Five à Créteil',
            description: $this->faker->sentence(50),
            location: '2 rue des poireaux, 94000 Créteil',
            date: new \DateTime('2024-01-09 20:00:00'),
            category: 'Sport',
            maxGuests: 5,
            host: $userDimitri,
            chat: $chatFive,

        );

        $meetFive
            ->addGuest($userMelinda)
            ->addGuest($userGeoffrey);
        $manager->persist($meetFive);

        $meetJeux = Meet::create(
            id: MeetId::create()->value(),
            title: 'Jeux de sociétés',
            description: $this->faker->sentence(50),
            location: '15 rue du Commerce, 94310 Orly',
            date: new \DateTime('2024-01-09 20:00:00'),
            category: 'jeux',
            maxGuests: 4,
            host: $userIngrid,
            chat: $chatJeux,
        );

        $meetJeux
            ->addGuest($userMelinda);
        $manager->persist($meetJeux);

        $meetClubbing = Meet::create(
            id: MeetId::create()->value(),
            title: 'Danser à la Favela',
            description: $this->faker->sentence(50),
            location: '15 rue du Faubourg du temple, 75010 Paris',
            date: new \DateTime('2024-01-09 20:00:00'),
            category: 'clubbing',
            maxGuests: 4,
            host: $userMelinda,
            chat: $chatClub,

        );
        $manager->persist($meetClubbing);

        $messageRaclette = Message::create(MessageId::create()->value());
        $messageRaclette
            ->setAuthor($userDimitri)
            ->setContent('Trop hate de me régaler !!')
            ->setCreatedAt(new \DateTime())
            ->setChat($chatRaclette);
        $manager->persist($messageRaclette);
        $manager->flush();

        $users = [];
        for ($i = 0; $i <= 3; ++$i) {
            /** @var string $sex */
            $sex = $this->faker->randomElement(['male, female']);
            $user = User::create(
                id: UserId::create()->value(),
                email: $this->faker->email(),
                password: $this->faker->password(10),
                sex: $sex,
                firstname: $this->faker->firstName($sex),
                lastname: $this->faker->lastName(),
                bio: $this->faker->sentence(),
                birthday: $this->faker->dateTimeBetween('-50 years', '-18 years'),
                city: $this->faker->city(),
                pictureUrl: 'https://media.licdn.com/dms/image/C4D03AQFwsiU89fQuHg/profile-displayphoto-shrink_800_800/0/1610137674745?e=1707955200&v=beta&t=4-_8BYbCE6J4wIm8pdpPHJQN74thveWfwwMzQDqWIQc'
            );
            $user->setPassword($this->userPasswordHasher->hashPassword($userIngrid, $userIngrid->getPassword()));
            $manager->persist($user);

            $users[] = $user;
        }

        for ($i = 0; $i <= 5; ++$i) {
            /** @var User $host */
            $host = $this->faker->randomElement($users);

            $maxGuests = $this->faker->numberBetween(1, count($users));

            /** @var User[] $guests */
            $guests = $this->faker->randomElements($users, $this->faker->numberBetween(1, $maxGuests));

            $chat = Chat::create(ChatId::create()->value());
            $chat->addChatter($host);
            $manager->persist($chat);

            /** @var string $meetCategory */
            $meetCategory = $this->faker->randomElement(['clubbing', 'restaurant', 'bar', 'travail', 'sport']);

            $meet = Meet::create(
                id: MeetId::create()->value(),
                title: $this->faker->sentence(7),
                description: $this->faker->sentence(50),
                location: $this->faker->address(),
                date: $this->faker->dateTimeThisYear(),
                category: $meetCategory,
                maxGuests: 4,
                host: $host,
                chat: $chat,

            );
            $manager->persist($meet);

            foreach ($guests as $guest) {
                $meet->addGuest($guest);
                $chat->addChatter($guest);
            }

            for ($i = 0; $i <= $this->faker->randomNumber(1); ++$i) {
                $message = Message::create(MessageId::create()->value());
                /** @var User $user */
                $user = $this->faker->randomElement($guests);
                $message
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
