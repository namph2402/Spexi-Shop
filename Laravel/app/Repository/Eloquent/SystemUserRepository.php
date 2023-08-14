<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\SystemUser;
use App\Repository\SystemUserRepositoryInterface;

class SystemUserRepository extends EloquentRepository implements SystemUserRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return SystemUser::class;
    }

}
