<?php

namespace App\Services\ProductServices;

use App\Container;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class DeleteProductService{

    private MySqlProductsRepository $products;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
    }

    public function execute(int $id){

        $this->products->delete($id);

        header('Location: /home/products');
    }

}