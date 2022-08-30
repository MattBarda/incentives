<?php

namespace App\Incentive\UI\Command;

use App\Incentive\Application\DTO\GetUserPoints;
use App\Incentive\Application\Service\GetUserPointsUseCase;
use Carbon\Carbon;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    'incentives:user:points',
    'Get user points for a given date'
)]
class GetUserPointsCommand extends Command
{
    private GetUserPointsUseCase $getUserPointsUseCase;

    public function __construct(GetUserPointsUseCase $getUserPointsUseCase)
    {
        parent::__construct();
        $this->getUserPointsUseCase = $getUserPointsUseCase;
    }

    protected function configure(): void
    {
        $this->addArgument('userId', InputArgument::REQUIRED, 'User id');
        $this->addArgument(
            'pointsForDate',
            InputArgument::OPTIONAL,
            'Date to calculate points for',
            Carbon::create('now')->format('Y-m-d H:i:s')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $inputData = $input->getArguments();
        $getUserPoints = GetUserPoints::fromUICommandArray($inputData);
        $points = $this->getUserPointsUseCase->execute($getUserPoints);

        $io->success(sprintf(
            'Points balance for user: %s for date: %s is: %s',
            $getUserPoints->getUserId(),
            $getUserPoints->getPointsForDate()->toString(),
            $points

        ));
        return Command::SUCCESS;
    }
}
