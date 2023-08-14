<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Banner;
use App\Repository\BannerRepositoryInterface;

class BannerRepository extends EloquentRepository implements BannerRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Banner::class;
    }

}
