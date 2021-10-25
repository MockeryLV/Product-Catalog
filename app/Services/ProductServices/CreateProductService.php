<?php

namespace App\Services\ProductServices;

use App\Container;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class CreateProductService{

    private MySqlProductsRepository $products;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
    }

    public function execute(){

        return new View('create.view.twig',
            [
                'categories' => $this->products->categories()->getAllCategories()->getCategories(),
                'tags' => $this->products->tags()->getAllTag()->getTags()
            ]);
    }

}