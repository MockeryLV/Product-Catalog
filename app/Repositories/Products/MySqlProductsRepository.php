<?php

namespace App\Repositories\Products;

use App\Models\Collections\ProductCollection;
use App\Models\Product;
use App\Repositories\ProductsRepository;
use PDO;

class MySqlProductsRepository implements ProductsRepository
{

    private PDO $connection;

    public function __construct()
    {
        require_once 'app/Repositories/config.php';
        $this->connection = new PDO($dsn, $user, $password);

    }


    public function getAll(): ProductCollection
    {
        $products = [];

        $sql = 'SELECT * FROM products';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $products[] = new Product($row['id'], $row['name'], $row['description'], $row['quantity'], $row['price'], $row['user_id']);
        }

        return new ProductCollection($products);
    }

}