<?php

namespace App\Controllers;

use App\Models\Product;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class ProductsController{

    private MySqlProductsRepository $products;

    public function __construct()
    {
        $this->products = new MySqlProductsRepository();
    }

    public function index(): View{

        $products = $this->products->getAll()->getProducts();
        return new View('index.view.twig', ['products' => $products]);

    }

    public function details(int $id): View{

        $product = $this->products->getById($id);

        return new View('details.view.twig', ['product' => $product]);

    }

    public function create(): View{


        return new View('create.view.twig', ['categories' => $this->products->categories()->getAllCategories()->getCategories()]);

    }

    public function insert(): void{



        $this->products->insert($_POST);

        header('Location: /product/index');
    }

    public function delete(int $id): void{


        $this->products->delete($id);

        header('Location: /product/index');
    }

    public function edit(int $id): View{

        $product = $this->products->getById($id);

        return new View('edit.view.twig', ['product' => $product]);

    }

    public function update(): void{

        $this->products->update($_POST);

        header('Location: /product/index');
    }


}