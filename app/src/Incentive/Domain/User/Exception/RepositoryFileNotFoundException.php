<?php

namespace App\Incentive\Domain\User\Exception;

use Exception;

class RepositoryFileNotFoundException extends Exception
{
    public static function reason(string $filename): self
    {
        return new self(sprintf(
            'File not fount in path: %s',
            $filename,
        ));
    }
}
