<?php

namespace App\Services\ProductServices;
use App\Container;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class UserProductDetailsService{

    private MySqlProductsRepository $products;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
    }

    public function execute(int $id){

        $product = $this->products->getById($id);

        return new View('user/user.details.view.twig', ['product' => $product]);
    }

}