<?php

namespace App\Services;

use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use App\Services\ServiceInterfaces\UserServiceInterface;

class UserService extends AbstractService implements UserServiceInterface
{
    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);
    }
}
