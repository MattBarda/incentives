<?php

namespace App\Tests\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\ValueObject\EmailAddress;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    public function testICreatesEmailAddress(): void
    {
        $sut = EmailAddress::fromString('aaa@domain.com');
        $this->assertEquals('aaa@domain.com', $sut->toString());
    }

    public function testItThrowsAnExceptionWhenSuppliedInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);
        EmailAddress::fromString('aaadomain.com');
    }
}
