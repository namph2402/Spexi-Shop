<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\PaymentTransaction;
use App\Repository\PaymentTransactionRepositoryInterface;

class PaymentTransactionRepository extends EloquentRepository implements PaymentTransactionRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return PaymentTransaction::class;
    }

}
