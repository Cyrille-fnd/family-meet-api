<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Entity\Guest;
use App\Domain\Entity\Host;
use App\Domain\Entity\Meet;
use App\Domain\Entity\User;
use App\Domain\Service\UserCreatorInterface;
use App\Domain\ValueObject\Category;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\ChatId;
use App\Domain\ValueObject\Identity\GuestId;
use App\Domain\ValueObject\Identity\HostId;
use App\Domain\ValueObject\Identity\MeetId;
use App\Domain\ValueObject\Identity\MessageId;
use App\Domain\ValueObject\Identity\UserId;
use App\Domain\ValueObject\RegisterInformation;
use App\Domain\ValueObject\Sex;
use App\Entity\Chat;
use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

final class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private UserCreatorInterface $userCreator,
    ) {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $userCyrille = $this->userCreator->create(RegisterInformation::create(
            id: UserId::create(),
            email: 'ferandc@gmail.com',
            password: 'zz',
            sex: Sex::MALE,
            firstname: 'Cyrille',
            lastname: 'Ferand',
            bio: 'Ici pour le fun !!',
            birthday: DateTimeImmutable::fromString('1989-02-06'),
            createdAt: DateTimeImmutable::create(),
            updatedAt: DateTimeImmutable::create(),
            city: 'Villejuif',
            pictureUrl: 'https://media.licdn.com/dms/image/C4D03AQFwsiU89fQuHg/profile-displayphoto-shrink_800_800/0/1610137674745?e=1707955200&v=beta&t=4-_8BYbCE6J4wIm8pdpPHJQN74thveWfwwMzQDqWIQc'
        ));
        $manager->persist($userCyrille);

        $userMelinda = $this->userCreator->create(RegisterInformation::create(
            id: UserId::create(),
            email: 'apatoutm@gmail.com',
            password: 'zz',
            sex: Sex::FEMALE,
            firstname: 'Melinda',
            lastname: 'Apatout',
            bio: 'Ici par curiosité',
            birthday: DateTimeImmutable::fromString('2000-08-20'),
            createdAt: DateTimeImmutable::create(),
            updatedAt: DateTimeImmutable::create(),
            city: 'Orly',
            pictureUrl: null
        ));
        $manager->persist($userMelinda);

        $userGeoffrey = $this->userCreator->create(RegisterInformation::create(
            id: UserId::create(),
            email: 'apatoutg@gmail.com',
            password: 'zz',
            sex: Sex::MALE,
            firstname: 'Geoffrey',
            lastname: 'Apatout',
            bio: '',
            birthday: DateTimeImmutable::fromString('1986-07-02'),
            createdAt: DateTimeImmutable::create(),
            updatedAt: DateTimeImmutable::create(),
            city: 'Orly',
            pictureUrl: null
        ));
        $manager->persist($userGeoffrey);

        $userDimitri = $this->userCreator->create(RegisterInformation::create(
            id: UserId::create(),
            email: 'niced@gmail.com',
            password: 'zz',
            sex: Sex::MALE,
            firstname: 'Dimitri',
            lastname: 'Nice',
            bio: '',
            birthday: DateTimeImmutable::fromString('1987-11-26'),
            createdAt: DateTimeImmutable::create(),
            updatedAt: DateTimeImmutable::create(),
            city: 'Thiais',
            pictureUrl: null
        ));
        $manager->persist($userDimitri);

        $userIngrid = $this->userCreator->create(RegisterInformation::create(
            id: UserId::create(),
            email: 'apatouti@gmail.com',
            password: 'zz',
            sex: Sex::FEMALE,
            firstname: 'Ingrid',
            lastname: 'Apatout',
            bio: '',
            birthday: DateTimeImmutable::fromString('1977-06-06'),
            createdAt: DateTimeImmutable::create(),
            updatedAt: DateTimeImmutable::create(),
            city: 'Orly',
            pictureUrl: null
        ));
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
            id: MeetId::create(),
            title: 'Raclette chez Cyrille',
            description: $this->faker->sentence(50),
            location: 'Chez Cyrille',
            date: DateTimeImmutable::fromDateTime($this->faker->dateTimeBetween('now', '+1 years')),
            category: Category::RESTAURANT,
            maxGuests: 6,
            createdAt: DateTimeImmutable::create(),
            updatedAt: DateTimeImmutable::create(),
            chat: $chatRaclette,
        );

        $hostMeetRaclette = Host::create(HostId::create(), $userCyrille, $meetRaclette);
        $meetRaclette->setHost($hostMeetRaclette);

        $meetRaclette
            ->addGuest(Guest::create(GuestId::create(), $userMelinda, $meetRaclette))
            ->addGuest(Guest::create(GuestId::create(), $userGeoffrey, $meetRaclette));
        $manager->persist($meetRaclette);

        $users = [];
        for ($i = 0; $i <= 3; ++$i) {
            /** @var Sex $sex */
            $sex = $this->faker->randomElement(Sex::class);
            $user = $this->userCreator->create(RegisterInformation::create(
                id: UserId::create(),
                email: $this->faker->email(),
                password: $this->faker->password(10),
                sex: $sex,
                firstname: $this->faker->firstName($sex),
                lastname: $this->faker->lastName(),
                bio: $this->faker->sentence(),
                birthday: DateTimeImmutable::fromDateTime($this->faker->dateTimeBetween('-50 years', '-18 years')),
                createdAt: DateTimeImmutable::create(),
                updatedAt: DateTimeImmutable::create(),
                city: $this->faker->city(),
                pictureUrl: 'https://media.licdn.com/dms/image/C4D03AQFwsiU89fQuHg/profile-displayphoto-shrink_800_800/0/1610137674745?e=1707955200&v=beta&t=4-_8BYbCE6J4wIm8pdpPHJQN74thveWfwwMzQDqWIQc'
            ));
            $manager->persist($user);

            $users[] = $user;
        }

        for ($i = 0; $i <= 5; ++$i) {
            /** @var User $host */
            $host = $this->faker->randomElement($users);

            $maxGuests = $this->faker->numberBetween(1, \count($users));

            /** @var User[] $guests */
            $guests = $this->faker->randomElements($users, $this->faker->numberBetween(1, $maxGuests));

            $chat = Chat::create(ChatId::create()->value());
            $chat->addChatter($host);
            $manager->persist($chat);

            /** @var Category $meetCategory */
            $meetCategory = $this->faker->randomElement(Category::class);

            $meet = Meet::create(
                id: MeetId::create(),
                title: $this->faker->sentence(7),
                description: $this->faker->sentence(50),
                location: $this->faker->address(),
                date: DateTimeImmutable::fromDateTime($this->faker->dateTimeBetween('now', '+1 years')),
                category: $meetCategory,
                maxGuests: 4,
                createdAt: DateTimeImmutable::create(),
                updatedAt: DateTimeImmutable::create(),
                chat: $chat,
            );
            $manager->persist($meet);

            $hostMeet = Host::create(HostId::create(), $host, $meet);
            $meet->setHost($hostMeet);

            foreach ($guests as $guest) {
                $meet->addGuest(Guest::create(GuestId::create(), $guest, $meet));
                $chat->addChatter($guest);
            }

            for ($i = 0; $i <= $this->faker->randomNumber(1); ++$i) {
                $message = Message::create(MessageId::create()->value());
                /** @var User $user */
                $user = $this->faker->randomElement($guests);
                $message
                    ->setAuthor($user)
                    ->setContent($this->faker->sentence(7))
                    ->setCreatedAt(DateTimeImmutable::create())
                    ->setChat($chat);
                $manager->persist($message);
            }
        }

        $manager->flush();
    }
}
