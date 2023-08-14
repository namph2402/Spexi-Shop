<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\UserProfile;
use App\Repository\UserProfileRepositoryInterface;

class UserProfileRepository extends EloquentRepository implements UserProfileRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return UserProfile::class;
    }

}
