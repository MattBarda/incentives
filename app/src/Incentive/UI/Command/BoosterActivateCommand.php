<?php

namespace App\Incentive\UI\Command;

use App\Incentive\Domain\User\Command\BoosterActivate;
use App\Incentive\Domain\User\Command\DeliveryComplete;
use Broadway\CommandHandling\CommandBus;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    'incentives:booster:activate',
    'Activate a booster'
)]
class BoosterActivateCommand extends Command
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
        $this->addArgument('validFrom', InputArgument::REQUIRED, 'valid from');
        $this->addArgument('validTo', InputArgument::REQUIRED, 'valid to');
        $this->addArgument('applicableForAction', InputArgument::REQUIRED, 'applicable for action');
        $this->addArgument('boosterBonusPoints', InputArgument::REQUIRED, 'booster bonus points');
        $this->addArgument('boosterBonusPointsValidFor', InputArgument::REQUIRED, 'booster bonus points valid for in days as int');
        $this->addArgument('boosterActionsRequired', InputArgument::REQUIRED, 'number of actions required to get booster points');
        $this->addArgument(
            'appliedAt',
            InputArgument::OPTIONAL,
            'Booster applied at',
            Carbon::create('now')->format('Y-m-d H:i:s')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //TODO user id should come from Repository - nextId method
        $boosterId = Uuid::uuid4();

        $inputData = $input->getArguments();
        $inputData['boosterId'] = $boosterId->toString();
        $this->commandBus->dispatch(BoosterActivate::fromArray($inputData));

        return Command::SUCCESS;
    }
}
