<?php

namespace App\Controllers;

use App\Container;
use App\Models\Product;
use App\Repositories\Products\MySqlProductsRepository;
use App\View;

class ProductsController
{

    private MySqlProductsRepository $products;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
    }

    public function index(): View
    {

        if (isset($_GET['name'])) {
            $products = [$this->products->getByName($_GET['name'])];
        } elseif (isset($_GET['category'])) {
            $products = $this->products->getByCategory($_GET['category'])->getProducts();

        } elseif (isset($_GET['tags'])) {
            $products = $this->products->getByTags($_GET['tags'])->getProducts();
        } else {
            $products = $this->products->getAll()->getProducts();
        }
        $categories = $this->products->categories()->getAllCategories()->getCategories();
        $tags = $this->products->tags()->getAllTag()->getTags();
        return new View('index.view.twig', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $_GET['category'],
            'tags' => $tags,
            'currentTag' => $_GET['tag']
        ]);

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


        return new View('create.view.twig',
            [
                'categories' => $this->products->categories()->getAllCategories()->getCategories(),
                'tags' => $this->products->tags()->getAllTag()->getTags()
            ]);

    }

    public function insert(): void
    {

        $this->products->insert($_POST) ? header('Location: /home/products') : header('Location: /product/create');

    }

    public function delete(int $id): void
    {


        $this->products->delete($id);

        header('Location: /home/products');
    }

    public function edit(int $id): View
    {

        $product = $this->products->getById($id);
        $tags = $this->products->tags()->getAllTag()->getTags();
        return new View('user/user.edit.view.twig', ['product' => $product, 'tags' => $tags]);

    }

    public function update(): void
    {

        $this->products->update($_POST) ? header("Location: /home/products/details/" . $_POST['id']) : header('Location: /product/edit/' . $_POST['id']);;



    }

    public function indexByUserId(): View
    {


        $products = $this->products->getByUserId($_SESSION['id'])->getProducts();

        return new View('user/user.index.view.twig', ['products' => $products]);

    }


}