<?php

namespace App\Tests\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\ValueObject\BoosterId;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BoosterIdTest extends TestCase
{
    public function testItThrowsExceptionWhenProvidedNonUuidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        BoosterId::fromString('aaa');
    }

    public function testItCreatesUuid(): void
    {
        $sut = BoosterId::fromString('00000000-0000-0000-0000-000000000000');
        $this->assertEquals('00000000-0000-0000-0000-000000000000', $sut->toString());
    }
}
