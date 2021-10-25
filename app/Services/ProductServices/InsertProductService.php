<?php

namespace App\Services\ProductServices;

use App\Container;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class InsertProductService{

    private MySqlProductsRepository $products;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
    }

    public function execute(array $product){

        $this->products->insert($_POST) ? header('Location: /home/products') : header('Location: /product/create');

    }

}