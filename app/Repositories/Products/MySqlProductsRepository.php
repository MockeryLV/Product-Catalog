<?php

namespace App\Repositories\Products;

use App\Models\Category;
use App\Models\Collections\CategoryCollection;
use App\Models\Collections\ProductCollection;
use App\Models\Product;
use App\Repositories\Categories\MySqlCategoriesRepository;
use App\Repositories\ProductsRepository;
use PDO;

class MySqlProductsRepository implements ProductsRepository
{

    private PDO $connection;

    private MySqlCategoriesRepository $categories;

    public function __construct()
    {
        require_once 'app/Repositories/config.php';
        $this->connection = new PDO($dsn, $user, $password);
        $this->categories = new MySqlCategoriesRepository();
    }


    public function categories(): MySqlCategoriesRepository
    {
        return $this->categories;
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


    public function getById(int $id): Product
    {
        $sql = 'SELECT * FROM products WHERE id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $categories = $this->categories->getCategoriesByProductId($id)->getCategories();

        return new Product(
            $product['id'],
            $product['name'],
            $product['description'],
            $product['quantity'],
            $product['price'],
            $product['user_id'],
            $categories
        );

    }

    public function getByName(string $name): Product
    {

        $sql = 'SELECT * FROM products WHERE name = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$name]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Product(
            $product['id'],
            $product['name'],
            $product['description'],
            $product['quantity'],
            $product['price'],
            $product['user_id'],
        );

    }


    public function insert(array $product): void
    {
        /*
         *  * will add return type (successfully or not)
         */

        $sql = 'INSERT INTO products (name, description, quantity, price, user_id) VALUES (?,?,?,?,?)';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            $product['name'],
            $product['description'],
            $product['quantity'],
            $product['price'] * 100, // multiplied by 100 to save float values as integers (10 will be saved as 1000)
            1
        ]);

        $productInfo = $this->getByName($product['name']);


        foreach ($product['categories'] as $category) {
            $sql = 'INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['product_id' => $productInfo->getId(), 'category_id' => $this->categories()->getCategoryByName($category)->getId()]);
        }

    }


    public function delete(int $id): void
    {
        $sql = 'DELETE FROM products WHERE id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
    }

    public function update(array $product)
    {
        $sql = 'UPDATE products SET name=:name, description=:description, quantity=:quantity, price=:price WHERE id=:id';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'name' => $product['name'],
            'description' => $product['description'],
            'quantity' => $product['quantity'],
            'price' => $product['price'] * 100, // multiplied by 100 to save float values as integers (10 will be saved as 1000)
            'id' => $product['id']
        ]);
    }


}