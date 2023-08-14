<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Menu;
use App\Repository\MenuRepositoryInterface;

class MenuRepository extends EloquentRepository implements MenuRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Menu::class;
    }

}
