<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\StoreInformation;
use App\Repository\StoreInformationRepositoryInterface;

class StoreInformationRepository extends EloquentRepository implements StoreInformationRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return StoreInformation::class;
    }

}
