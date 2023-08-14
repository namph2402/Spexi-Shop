<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\FormData;
use App\Repository\FormDataRepositoryInterface;

class FormDataRepository extends EloquentRepository implements FormDataRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return FormData::class;
    }

}
