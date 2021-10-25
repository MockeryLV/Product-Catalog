<?php

namespace App\Controllers;

use App\Container;
use App\DTOs\ProductData;
use App\Models\Product;
use App\Repositories\Products\MySqlProductsRepository;

use App\Services\ProductServices\AllProductListService;
use App\Services\ProductServices\CreateProductService;
use App\Services\ProductServices\DeleteProductService;
use App\Services\ProductServices\EditProductService;
use App\Services\ProductServices\InsertProductService;
use App\Services\ProductServices\ProductDetailsService;
use App\Services\ProductServices\UpdateProductService;
use App\Services\ProductServices\UserProductDetailsService;
use App\Services\ProductServices\UserProductListService;
use App\View;

class ProductsController
{


    private AllProductListService $allProductListService;
    private ProductDetailsService $productDetailsService;

    private UserProductDetailsService $userProductDetailsSerivice;
    private CreateProductService $createProductService;

    private InsertProductService $insertProductService;

    private DeleteProductService $deleteProductService;

    private EditProductService $editProductService;

    private UpdateProductService $updateProductService;

    private UserProductListService $userProductListService;

    public function __construct(Container $container)
    {

        $this->products = $container->get(MySqlProductsRepository::class);
        $this->allProductListService = new AllProductListService($container);
        $this->productDetailsService = new ProductDetailsService($container);
        $this->userProductDetailsSerivice = new UserProductDetailsService($container);
        $this->createProductService = new CreateProductService($container);
        $this->insertProductService = new InsertProductService($container);
        $this->deleteProductService = new DeleteProductService($container);
        $this->editProductService = new EditProductService($container);
        $this->updateProductService = new UpdateProductService($container);
        $this->userProductListService = new UserProductListService($container);
    }

    public function index(): View
    {

        $response = $this->allProductListService->execute((new ProductData($_GET))->getProductData());

        return $response;

    }

    public function details(int $id): View
    {

        $response = $this->productDetailsService->execute($id);
        return $response;

    }

    public function detailsByUser(int $id): View
    {
        $response = $this->userProductDetailsSerivice->execute($id);
        return $response;

    }

    public function create(): View
    {


        $response = $this->createProductService->execute();

        return $response;

    }

    public function insert(): void
    {

        $this->insertProductService->execute((new ProductData($_POST))->getProductData());

    }

    public function delete(int $id): void
    {

        $this->deleteProductService->execute($id);

    }

    public function edit(int $id): View
    {
        $response = $this->editProductService->execute($id);
        return $response;
    }

    public function update(): void
    {

        $this->updateProductService->execute((new ProductData($_POST))->getProductData());

    }

    public function indexByUserId(): View
    {

        $response = $this->userProductListService->execute($_SESSION['id']);
        return $response;

    }


}