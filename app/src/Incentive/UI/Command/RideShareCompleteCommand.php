<?php

namespace App\Incentive\UI\Command;

use App\Incentive\Domain\User\Command\RideShareComplete;
use Broadway\CommandHandling\CommandBus;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    'incentives:rideshare:complete',
    'Complete a ride-share'
)]
class RideShareCompleteCommand extends Command
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this->addArgument('userId', InputArgument::REQUIRED, 'User id');
        $this->addArgument(
            'completedAt',
            InputArgument::OPTIONAL,
            'Delivery completed At',
            Carbon::create('now')->format('Y-m-d H:i:s')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //TODO user id should come from Repository - nextId method
        $rideShareId = Uuid::uuid4();

        $inputData = $input->getArguments();
        $inputData['rideShareId'] = $rideShareId->toString();
        $this->commandBus->dispatch(RideShareComplete::fromArray($inputData));

        $io->success('Completed ride share with ID: '. $rideShareId);
        return Command::SUCCESS;
    }
}
