<?php

namespace App\Services\ProductServices;
use App\Container;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class UpdateProductService{

    private MySqlProductsRepository $products;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
    }

    public function execute(array $product): void{
        $this->products->update($product) ? header("Location: /home/products/details/" . $product['id']) : header('Location: /product/edit/' . $product['id']);;
    }

}