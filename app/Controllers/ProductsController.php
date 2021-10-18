<?php

namespace App\Controllers;

use App\Models\Product;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class ProductsController
{

    private MySqlProductsRepository $products;

    public function __construct()
    {
        $this->products = new MySqlProductsRepository();
    }

    public function index(): View
    {

        if ($_GET['name']) {
            $products = [$this->products->getByName($_GET['name'])];
        } elseif ($_GET['category']) {
            $products = $this->products->getByCategory($_GET['category'])->getProducts();

        } else {
            $products = $this->products->getAll()->getProducts();
        }
        $categories = $this->products->categories()->getAllCategories()->getCategories();
        return new View('index.view.twig', ['products' => $products, 'categories' => $categories, 'currentCategory' => $_GET['category']]);

    }

    public function details(int $id): View
    {

        $product = $this->products->getById($id);

        return new View('details.view.twig', ['product' => $product]);

    }

    public function detailsByUser(int $id): View
    {

        $product = $this->products->getById($id);

        return new View('user/user.details.view.twig', ['product' => $product]);

    }

    public function create(): View
    {


        return new View('create.view.twig', ['categories' => $this->products->categories()->getAllCategories()->getCategories()]);

    }

    public function insert(): void
    {


        $this->products->insert($_POST);

        header('Location: /home/products');
    }

    public function delete(int $id): void
    {


        $this->products->delete($id);

        header('Location: /product/index');
    }

    public function edit(int $id): View
    {

        $product = $this->products->getById($id);

        return new View('user/user.edit.view.twig', ['product' => $product]);

    }

    public function update(): void
    {

        $this->products->update($_POST);

        header('Location: /home/products');
    }

    public function indexByUserId(): View
    {


        $products = $this->products->getByUserId($_SESSION['id'])->getProducts();

        return new View('user/user.index.view.twig', ['products' => $products]);

    }


}