<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\User;
use App\Repository\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return User::class;
    }

}
