<?php

namespace App\Models;

class Category
{

    private string $category;
    private int $id;


    public function __construct(int $id, string $category)
    {

        $this->category = $category;
        $this->id = $id;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getId(): int
    {
        return $this->id;
    }
}