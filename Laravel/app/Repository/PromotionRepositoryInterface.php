<?php

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\Promotion;

interface PromotionRepositoryInterface extends RepositoryInterface
{
    public function attach(Promotion $promotion, $productId);
    public function detach(Promotion $promotion, $productId);
}
