<?php

namespace App\DataFixtures;

use App\Entity\Chat;
use App\Entity\Meet;
use App\Entity\Message;
use App\Entity\User;
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
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $userCyrille = new User();
        $userCyrille
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

        $chatRaclette = new Chat();
        $chatRaclette
            ->addChatter($userCyrille)
            ->addChatter($userMelinda)
            ->addChatter($userGeoffrey);
        $manager->persist($chatRaclette);

        $chatFive = new Chat();
        $chatFive->addChatter($userDimitri);
        $manager->persist($chatFive);

        $chatJeux = new Chat();
        $chatJeux
            ->addChatter($userIngrid)
            ->addChatter($userMelinda);
        $manager->persist($chatJeux);

        $chatClub = new Chat();
        $chatClub
            ->addChatter($userMelinda);
        $manager->persist($chatClub);

        $meetRaclette = new Meet();
        $meetRaclette
            ->setTitle('Raclette chez Cyrille')
            ->setDescription($this->faker->sentence(50))
            ->setLocation('Raclette chez Cyrille')
            ->setDate(new \DateTime('2024-01-09 20:00:00'))
            ->setCategory('restaurant')
            ->setMaxGuests(6)
            ->setHost($userCyrille)
            ->setChat($chatRaclette)
            ->addGuest($userMelinda)
            ->addGuest($userGeoffrey);
        $manager->persist($meetRaclette);

        $meetFive = new Meet();
        $meetFive
            ->setTitle('Five à Créteil')
            ->setDescription($this->faker->sentence(50))
            ->setLocation('2 rue des poireaux, 94000 Créteil')
            ->setDate(new \DateTime('2024-01-09 20:00:00'))
            ->setCategory('sport')
            ->setMaxGuests(5)
            ->setHost($userDimitri)
            ->setChat($chatFive);
        $manager->persist($meetFive);

        $meetJeux = new Meet();
        $meetJeux
            ->setTitle('Jeux de sociétés')
            ->setDescription($this->faker->sentence(50))
            ->setLocation('15 rue du Commerce, 94310 Orly')
            ->setDate(new \DateTime('2024-01-09 20:00:00'))
            ->setCategory('jeux')
            ->setMaxGuests(4)
            ->setHost($userIngrid)
            ->setChat($chatJeux)
            ->addGuest($userMelinda);
        $manager->persist($meetJeux);

        $meetClubbing = new Meet();
        $meetClubbing
            ->setTitle('Danser à la Favela')
            ->setDescription($this->faker->sentence(50))
            ->setLocation('15 rue du Faubourg du temple, 75010 Paris')
            ->setDate(new \DateTime('2024-01-09 20:00:00'))
            ->setCategory('clubbing')
            ->setMaxGuests(4)
            ->setHost($userMelinda)
            ->setChat($chatClub);
        $manager->persist($meetClubbing);

        $messageRaclette = new Message();
        $messageRaclette
            ->setAuthor($userDimitri)
            ->setContent('Trop hate de me régaler !!')
            ->setCreatedAt(new \DateTime())
            ->setChat($chatRaclette);
        $manager->persist($messageRaclette);

        $users = [];
        for ($i = 0; $i <= 3; ++$i) {
            $user = new User();
            /** @var string $sex */
            $sex = $this->faker->randomElement(['male, female']);
            $user
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

        for ($i = 0; $i <= 5; ++$i) {
            /** @var User $host */
            $host = $this->faker->randomElement($users);

            $maxGuests = $this->faker->numberBetween(1, count($users));

            /** @var User[] $guests */
            $guests = $this->faker->randomElements($users, $this->faker->numberBetween(1, $maxGuests));

            $chat = new Chat();
            $chat->addChatter($host);
            $manager->persist($chat);

            $meet = new Meet();
            /** @var string $meetCategory */
            $meetCategory = $this->faker->randomElement(['clubbing', 'restaurant', 'bar', 'travail', 'sport']);
            $meet
                ->setTitle($this->faker->sentence(7))
                ->setDescription($this->faker->sentence(50))
                ->setLocation($this->faker->address())
                ->setDate($this->faker->dateTimeThisYear())
                ->setCategory($meetCategory)
                ->setMaxGuests($maxGuests)
                ->setChat($chat)
                ->setHost($host);
            $manager->persist($meet);

            foreach ($guests as $guest) {
                $meet->addGuest($guest);
                $chat->addChatter($guest);
            }

            for ($i = 0; $i <= $this->faker->randomNumber(1); ++$i) {
                $message = new Message();
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
