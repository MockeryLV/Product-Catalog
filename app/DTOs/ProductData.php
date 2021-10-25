<?php


namespace App\DTOs;

class ProductData{

    private array $productData = [];

    public function __construct(array $productData)
    {

        $this->productData[] = $productData;
    }

    public function getProductData(): array
    {
        return $this->productData[0];
    }

}