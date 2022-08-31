<?php

namespace App\Tests\Incentive\Infrastructure\Repository\Projector;

use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use App\Incentive\Infrastructure\Repository\InMemoryRepository\UserPointsInMemoryRepository;
use App\Incentive\Infrastructure\Repository\Projector\UserWasRegisteredProjector;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class UserWasRegisteredProjectorTest extends TestCase
{
    public function test_it_creates_a_read_model_when_user_was_registered()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $userPointsInMemoryRepository = new UserPointsInMemoryRepository();
        $sut = new UserWasRegisteredProjector($userPointsInMemoryRepository);

        $sut->applyUserWasRegistered(new UserWasRegistered(
            UserId::fromString($userId),
            UserName::fromString($username),
            EmailAddress::fromString($userEmailAddress)
        ));

        $this->assertEquals(0, $userPointsInMemoryRepository->getPointsForDate($userId, Carbon::now()));
    }
}
