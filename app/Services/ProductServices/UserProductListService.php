<?php

namespace App\Services\ProductServices;

use App\Container;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class UserProductListService{

    private MySqlProductsRepository $products;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
    }

    public function execute(int $id): View{


        $products = $this->products->getByUserId($id)->getProducts();

        return new View('user/user.index.view.twig', ['products' => $products]);

    }

}