<?php

namespace App\Repositories;

interface CategoriesRepository
{

    public function getAllCategories();

    public function getCategoryByName(string $name);

    public function getCategoriesByProductId(int $id);

}