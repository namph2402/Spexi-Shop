<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PaymentMethod;
use App\Repository\PaymentMethodRepositoryInterface;

class PaymentMethodRepository extends EloquentRepository implements PaymentMethodRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PaymentMethod::class;
    }

}
