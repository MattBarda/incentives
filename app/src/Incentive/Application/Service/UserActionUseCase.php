<?php

namespace Incentive\Application\Service;

use Incentive\Application\DTO\UserActionDTO;

class UserActionUseCase
{
    public function execute(UserActionDTO $userActionDTO)
    {
        dd($userActionDTO);
    }
}
