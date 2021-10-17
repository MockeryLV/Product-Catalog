<?php
namespace App\Models\Collections;

use App\Models\Category;
use App\Models\Product;

class CategoryCollection
{


    private array $categories = [];

    public function __construct(array $categories)
    {
        foreach ($categories as $category) {
            if ($category instanceof Category) {
                $this->categories[] = $category;
            }
        }
    }


    public function getCategories(): array
    {
        return $this->categories;
    }

}