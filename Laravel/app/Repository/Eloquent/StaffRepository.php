<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Staff;
use App\Repository\StaffRepositoryInterface;

class StaffRepository extends EloquentRepository implements StaffRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Staff::class;
    }

}
