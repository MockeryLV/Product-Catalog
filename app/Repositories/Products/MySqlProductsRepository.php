<?php

namespace App\Repositories\Products;

use App\Models\Category;
use App\Models\Collections\CategoryCollection;
use App\Models\Collections\ProductCollection;
use App\Models\Product;
use App\Repositories\Categories\MySqlCategoriesRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\Tags\MySqlTagsRepository;
use App\Validations\InsertProductValidator;
use PDO;
use PDOException;

class MySqlProductsRepository implements ProductsRepository
{

    private PDO $connection;

    private MySqlCategoriesRepository $categories;

    private MySqlTagsRepository $tags;

    public function __construct()
    {
        require_once 'app/Repositories/config.php';


        try{
            $this->connection = new PDO($dsn, $user, $password);
        }catch(PDOException $e){
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        $this->categories = new MySqlCategoriesRepository();
        $this->tags = new MySqlTagsRepository();
    }


    public function tags(): MySqlTagsRepository
    {
        return $this->tags;
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
            $products[] = new Product(
                $row['id'],
                $row['name'],
                $row['description'],
                $row['quantity'],
                $row['price'],
                $row['user_id']
            );
        }

        return new ProductCollection($products);
    }


    public function getById(int $id): Product
    {
        $sql = 'SELECT * FROM products WHERE id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $categories = $this
            ->categories
            ->getCategoriesByProductId($id)
            ->getCategories();

        $tags = $this
            ->tags
            ->getTagsByProductId($id)
            ->getTags();


        return new Product(
            $product['id'],
            $product['name'],
            $product['description'],
            $product['quantity'],
            $product['price'],
            $product['user_id'],
            $categories,
            $tags
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


    public function getByUserId(int $id): ProductCollection
    {

        $products = [];

        $sql = 'SELECT * FROM products WHERE user_id = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $products[] = new Product(
                $row['id'],
                $row['name'],
                $row['description'],
                $row['quantity'],
                $row['price'],
                $row['user_id']
            );
        }

        return new ProductCollection($products);

    }

    public function getByCategory(string $category): ProductCollection
    {

        $products = [];


        $sql = 'SELECT id, name, description, quantity, price, user_id '
            . 'FROM categories LEFT JOIN product_categories USING (category_id)'
            . ' INNER JOIN products WHERE product_id = id AND category = :category;';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['category' => $category]);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $products[] = new Product(
                $row['id'],
                $row['name'],
                $row['description'],
                $row['quantity'],
                $row['price'],
                $row['user_id']
            );
        }

        return new ProductCollection($products);

    }

    public function getByTag(string $tag): ProductCollection
    {

        $products = [];


        $sql = 'SELECT id, name, description, quantity, price, user_id '
            . 'FROM tags LEFT JOIN product_tags USING (tag_id)'
            . ' INNER JOIN products WHERE product_id = id AND tag = :tag;';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['tag' => $tag]);

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $products[] = new Product(
                $row['id'],
                $row['name'],
                $row['description'],
                $row['quantity'],
                $row['price'],
                $row['user_id']
            );
        }

        return new ProductCollection($products);

    }




    public function insert(array $product): bool
    {
        /*
         *  * will add return type (successfully or not)
         */

        if(
            InsertProductValidator::nameValidate($product['name'])
            &&InsertProductValidator::categoryValidate($product['categories'])
            &&InsertProductValidator::descriptionValidate($product['description'])
            &&InsertProductValidator::priceValidate((int)$product['price'])
            &InsertProductValidator::quantityValidate((int)$product['quantity'])
            &&InsertProductValidator::tagsValidate($product['tags'])
        ){
            $sql = 'INSERT INTO products (name, description, quantity, price, user_id) VALUES (?,?,?,?,?)';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                $product['name'],
                $product['description'],
                $product['quantity'],
                $product['price'] * 100, // multiplied by 100 to save float values as integers (10 will be saved as 1000)
                $_SESSION['id']
            ]);

            $productInfo = $this->getByName($product['name']);


            foreach ($product['categories'] as $category) {
                $sql = 'INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)';
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([
                    'product_id' => $productInfo->getId(),
                    'category_id' => $this
                        ->categories()
                        ->getCategoryByName($category)
                        ->getId()
                ]);
            }
            if (!empty($product['tags'][0])) {
                foreach ($product['tags'] as $tag) {
                    $sql = 'INSERT INTO product_tags (product_id, tag_id) VALUES (:product_id, :tag_id)';
                    $stmt = $this->connection->prepare($sql);
                    $stmt->execute([
                        'product_id' => $productInfo->getId(),
                        'tag_id' => $this->tags()->getTagByName($tag)->getId()
                    ]);
                }
            }
            return true;
        }else{

            return false;
        }


    }


    public function delete(int $id): void
    {

        if ($_SESSION['id'] == $this->getById($id)->getUserId()) {
            $sql = 'DELETE FROM products WHERE id = :id AND user_id=:user_id';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'user_id' => $_SESSION['id']
            ]);
            $sql = 'DELETE FROM product_categories WHERE product_id = ?';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$id]);
        }


    }

    public function update(array $product): bool
    {
        if(
            InsertProductValidator::nameValidate($product['name'])
            &&InsertProductValidator::descriptionValidate($product['description'])
            &&InsertProductValidator::priceValidate((int)$product['price'])
            &InsertProductValidator::quantityValidate((int)$product['quantity'])
            &&InsertProductValidator::tagsValidate($product['tags'])
        ){

            $sql = 'UPDATE products SET name=:name,'
                . ' description=:description, quantity=:quantity, price=:price '
                . 'WHERE id=:id AND user_id=:user_id';

            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'name' => $product['name'],
                'description' => $product['description'],
                'quantity' => $product['quantity'],
                'price' => $product['price'] * 100, // multiplied by 100 to save float values as integers (10 will be saved as 1000)
                'id' => $product['id'],
                'user_id' => $_SESSION['id']
            ]);
            if ($product['tags']) {

                $sql = 'DELETE FROM product_tags WHERE product_id = :product_id';
                $stmt = $this->connection->prepare($sql);
                $stmt->execute(['product_id' => $product['id']]);

                if (!empty($product['tags'][0])) {
                    foreach ($product['tags'] as $tag) {
                        /*
                         * @var Tag $tag
                         */
                        if ($this->tags->getTagByName($tag)) {
                            $sql = 'INSERT INTO product_tags (product_id, tag_id) VALUES (:product_id, :tag_id)';
                            $stmt = $this->connection->prepare($sql);
                            $stmt->execute([
                                'product_id' => $product['id'],
                                'tag_id' => $this
                                    ->tags
                                    ->getTagByName($tag)
                                    ->getId()
                            ]);
                        }
                    }
                }


            }
            return true;
        }
        return false;



    }


}