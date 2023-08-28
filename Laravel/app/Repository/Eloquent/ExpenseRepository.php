<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Expense;
use App\Repository\ExpenseRepositoryInterface;

class ExpenseRepository extends EloquentRepository implements ExpenseRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Expense::class;
    }

}
