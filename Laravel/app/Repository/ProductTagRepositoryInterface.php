<?php

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\ProductTag;

interface ProductTagRepositoryInterface extends RepositoryInterface
{
    public function attach(ProductTag $tag, $productId);
    public function detach(ProductTag $tag, $productId);
}
