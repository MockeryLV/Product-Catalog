<?php


namespace App\Repositories;

use App\Models\Product;

interface ProductsRepository
{

    public function getAll();

    public function getById(int $id);

    public function insert(array $product);

    public function delete(int $id);

    public function update(array $product);

    public function getByName(string $name);

    public function categories();


}