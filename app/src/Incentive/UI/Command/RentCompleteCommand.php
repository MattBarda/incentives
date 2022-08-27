<?php

namespace App\Incentive\UI\Command;

use App\Incentive\Domain\User\Command\RentComplete;
use Broadway\CommandHandling\CommandBus;
use Carbon\Carbon;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    'incentives:rent:complete',
    'Complete a rent'
)]
class RentCompleteCommand extends Command
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
        $this->addArgument('rentId', InputArgument::REQUIRED, 'Rent id');
        $this->addArgument(
            'completedAt',
            InputArgument::OPTIONAL,
            'Rent started at',
            Carbon::create('now')->format('Y-m-d H:i:s')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputData = $input->getArguments();
        $this->commandBus->dispatch(RentComplete::fromArray($inputData));

        return Command::SUCCESS;
    }
}
