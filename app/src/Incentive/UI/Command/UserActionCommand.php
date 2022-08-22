<?php

namespace Incentive\UI\Command;

use Carbon\Carbon;
use Incentive\Application\DTO\UserActionDTO;
use Incentive\Application\Service\UserActionUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    'user:action',
    'Performs User Action'
)]
class UserActionCommand extends Command
{
    /**
     * @var UserActionUseCase
     */
    private $userActionUseCase;

    public function __construct(UserActionUseCase $userActionUseCase)
    {
        parent::__construct();
        $this->userActionUseCase = $userActionUseCase;
    }

    protected function configure(): void
    {
        $this->addArgument('user_id', InputArgument::REQUIRED, 'User id');
        $this->addArgument('action_type', InputArgument::REQUIRED, 'Type of action performed by user');
        $this->addArgument(
            'finished_at',
            InputArgument::OPTIONAL,
            'Date and time when action finished in Y-m-d H:s format',
            Carbon::create('now')->format('Y-m-d H:i:s')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputData = $input->getArguments();
        $userActionDTO = UserActionDTO::fromArray($inputData);
        $this->userActionUseCase->execute($userActionDTO);

        return Command::SUCCESS;
    }
}
