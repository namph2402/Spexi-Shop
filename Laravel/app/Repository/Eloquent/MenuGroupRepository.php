<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\MenuGroup;
use App\Repository\MenuGroupRepositoryInterface;

class MenuGroupRepository extends EloquentRepository implements MenuGroupRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return MenuGroup::class;
    }

}
