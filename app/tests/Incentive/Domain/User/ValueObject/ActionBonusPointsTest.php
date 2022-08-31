<?php

namespace App\Tests\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use PHPUnit\Framework\TestCase;

class ActionBonusPointsTest extends TestCase
{
    public function testItCreatesActionBonusPoints(): void
    {
        $sut = ActionBonusPoints::fromInt(3);
        $this->assertEquals(3, $sut->toInt());
    }

    public function testItMultipliesBonusPoints(): void
    {
        $sut = ActionBonusPoints::fromInt(3);
        $this->assertEquals(9, $sut->multiplyBy(3)->toInt());
    }
}
