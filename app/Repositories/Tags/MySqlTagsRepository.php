<?php

namespace App\Repositories\Tags;


use App\Models\Category;
use App\Models\Collections\TagsCollection;
use App\Models\Tag;
use PDO;

class MySqlTagsRepository
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


    public function getAllTag(): TagsCollection
    {

        $tags = [];

        $sql = 'SELECT * FROM tags';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $tags[] = new Tag($row['tag_id'], $row['tag']);
        }

        return new TagsCollection($tags);

    }


    public function getTagByName(string $name): Tag
    {

        $sql = 'SELECT * FROM tags WHERE tag = ?';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$name]);

        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Tag($category['tag_id'], $category['tag']);
    }


    public function getTagsByProductId(int $id): TagsCollection
    {


        $sql = 'SELECT tag, tag_id FROM tags LEFT JOIN product_tags USING(tag_id) INNER JOIN products WHERE id = :id AND product_id = :id;';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        $tags = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $tags[] = new Tag($row['tag_id'], $row['tag']);
        }

        return new TagsCollection($tags);

    }

}