<?php

namespace App\Repositories\Categories;

use App\Models\Category;
use App\Models\Collections\CategoryCollection;
use App\Models\Collections\ProductCollection;
use App\Models\Product;
use App\Repositories\CategoriesRepository;
use App\Repositories\ProductsRepository;
use PDO;

class MySqlCategoriesRepository implements CategoriesRepository
{

    private PDO $connection;

    public function __construct()
    {
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $dbname = 'products_catalog';

        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
        $this->connection = new PDO($dsn, $user, $password);
    }


    public function getAllCategories(): CategoryCollection
    {

        $categories = [];

        $sql = 'SELECT * FROM categories';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $categories[] = new Category(
                $row['category_id'],
                $row['category']
            );
        }

        return new CategoryCollection($categories);

    }

    public function getCategoriesByProductId(int $id): CategoryCollection
    {

        $sql = 'SELECT category, category_id FROM categories LEFT JOIN'
            .' product_categories USING(category_id) '
            .'INNER JOIN products WHERE id = :id AND product_id = :id;';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        $categories = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $categories[] = new Category(
                $row['category_id'],
                $row['category']
            );
        }

        return new CategoryCollection($categories);

    }


    public function getCategoryByName(string $name): Category
    {

        $sql = 'SELECT * FROM categories WHERE category = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$name]);

        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Category(
            $category['category_id'],
            $category['category']
        );
    }

}