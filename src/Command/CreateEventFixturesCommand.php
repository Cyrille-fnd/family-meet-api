<?php

namespace App\Command;

use App\Entity\User;
use App\Service\ElasticSearch\ElasticSearchClientGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:load-event-fixtures',
    description: 'Load events fixtures',
    hidden: false
)]
class CreateEventFixturesCommand extends Command
{
    public function __construct(
        private ElasticSearchClientGeneratorInterface $clientGenerator,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('start loading event fixtures');

        $faker = Factory::create();
        $client = $this->clientGenerator->getClient();

        $users = $this->entityManager->getRepository(User::class)->findAll();

        for ($i = 0; $i <= 100; ++$i) {
            /** @var User $host */
            $host = $faker->randomElement($users);

            $max = $faker->numberBetween(1, 20);

            /** @var User[] $guests */
            $guests = $faker->randomElements($users, $faker->numberBetween(1, $max));
            $guests = array_map(function (User $guest) {
                return $guest->getId();
            }, $guests);

            $event = [
                'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
                'id' => $faker->uuid(),
                'body' => [
                    'title' => $faker->sentence(7),
                    'description' => $faker->sentence(50),
                    'location' => $faker->address(),
                    'date' => $faker->dateTimeThisYear()->format('Y-m-d h:i:s'),
                    'category' => $faker->randomElement([
                        'club',
                        'bar',
                        'travail',
                        'sport',
                        'cinema',
                        'voyage',
                        'restaurant',
                    ]),
                    'participantMax' => $max = $faker->numberBetween(1, 20),
                    'createdAt' => $faker->dateTimeBetween('-2 years', '-3 months')
                        ->format('Y-m-d h:i:s'),
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
        $output->writeln('loading event fixtures done !');

        return Command::SUCCESS;
    }
}
