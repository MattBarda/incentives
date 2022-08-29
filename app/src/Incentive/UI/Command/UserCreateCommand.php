<?php

namespace App\Incentive\UI\Command;

use App\Incentive\Domain\User\Command\RegisterUser;
use Broadway\CommandHandling\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    'incentives:user:create',
    'Creates new User for Incentives context'
)]
class UserCreateCommand extends Command
{

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this->addArgument('userName', InputArgument::REQUIRED, 'User name');
        $this->addArgument('emailAddress', InputArgument::REQUIRED, 'User email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //TODO user id should come from Repository - nextId method
        $userId = Uuid::uuid4();

        $inputData = $input->getArguments();
        $inputData['userId'] = $userId->toString();
        $this->commandBus->dispatch(RegisterUser::fromArray($inputData));

        $io->success('Creted user with ID: '.$userId);
        return Command::SUCCESS;
    }
}
