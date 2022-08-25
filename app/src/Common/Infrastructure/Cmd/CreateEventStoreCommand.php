<?php

namespace App\Common\Infrastructure\Cmd;

use Broadway\EventStore\Dbal\DBALEventStore;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    'broadway:event-store:create',
    'Creates the event store schema'
)]
class CreateEventStoreCommand extends Command
{
    private Connection $connection;
    private DBALEventStore $eventStore;

    public function __construct(Connection $connection, DBALEventStore $eventStore)
    {
        parent::__construct();
        $this->connection = $connection;
        $this->eventStore = $eventStore;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($table = $this->eventStore->configureSchema($schemaManager->createSchema())) {
            $schemaManager->createTable($table);
            $output->writeln('<info>Created Broadway event store schema</info>');
        } else {
            $output->writeln('<info>Broadway event store schema already exists</info>');
        }

        return Command::SUCCESS;
    }
}
