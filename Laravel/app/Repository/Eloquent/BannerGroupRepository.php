<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\BannerGroup;
use App\Repository\BannerGroupRepositoryInterface;

class BannerGroupRepository extends EloquentRepository implements BannerGroupRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return BannerGroup::class;
    }

}
