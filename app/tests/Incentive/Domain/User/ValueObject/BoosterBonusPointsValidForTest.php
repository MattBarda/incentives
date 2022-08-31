<?php

namespace App\Tests\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\ValueObject\BoosterBonusPointsValidFor;
use PHPUnit\Framework\TestCase;

class BoosterBonusPointsValidForTest extends TestCase
{
    public function testItCreatesDateIntervalCorrectly(): void
    {
        $sut = BoosterBonusPointsValidFor::fromDaysAsInt(2);
        $this->assertEquals(2, $sut->toInt());
        $this->assertEquals(new \DateInterval('P2D'), $sut->toDateInterval());
    }
}
