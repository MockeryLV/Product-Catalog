<?php

namespace App\Models\Collections;

use App\Models\Product;

class ProductCollection
{


    private array $products = [];

    public function __construct(array $products)
    {
        foreach ($products as $product) {
            if ($product instanceof Product) {
                $this->products[] = $product;
            }
        }
    }


    public function getProducts(): array
    {
        return $this->products;
    }

}