<?php

namespace App\Repository;


use App\Common\RepositoryInterface;
use App\Models\Product;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function attach(Product $product, $tagId);

    public function detach(Product $product, $tagId);
}
