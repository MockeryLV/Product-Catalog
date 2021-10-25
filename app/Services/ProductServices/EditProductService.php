<?php

namespace App\Services\ProductServices;

use App\Container;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class EditProductService{

    private MySqlProductsRepository $products;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
    }

    public function execute(int $id): view{


        $product = $this->products->getById($id);
        $tags = $this->products->tags()->getAllTag()->getTags();
        return new View('user/user.edit.view.twig', ['product' => $product, 'tags' => $tags]);


    }

}