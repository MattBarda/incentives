<?php

namespace App\Tests\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserBonusPoints;
use PHPUnit\Framework\TestCase;

class UserBonusPointsTest extends TestCase
{
    public function testItCreatesUserBonusPoints(): void
    {
        $sut = UserBonusPoints::fromInt(5);
        $this->assertEquals(5, $sut->toInt());
    }

    public function testItAddsActionBonusPoints(): void
    {
        $sut = UserBonusPoints::addActionBonusPoints(
            UserBonusPoints::fromInt(5),
            ActionBonusPoints::fromInt(5)
        );
        $this->assertEquals(10, $sut->toInt());
    }
}
