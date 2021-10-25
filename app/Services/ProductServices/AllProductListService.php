<?php

namespace App\Services\ProductServices;

use App\Container;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class AllProductListService{

    private MySqlProductsRepository $products;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
    }

    public function execute(array $info){
        if (isset($info['name'])) {
            $products = [$this->products->getByName($info['name'])];
        } elseif (isset($info['category'])) {
            $products = $this->products->getByCategory($info['category'])->getProducts();

        } elseif (isset($info['tags'])) {
            $products = $this->products->getByTags($info['tags'])->getProducts();
        } else {
            $products = $this->products->getAll()->getProducts();
        }
        $categories = $this->products->categories()->getAllCategories()->getCategories();
        $tags = $this->products->tags()->getAllTag()->getTags();
        return new View('index.view.twig', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $info['category'],
            'tags' => $tags,
            'currentTag' => $info['tag']
        ]);
    }

}