<?php

namespace App\Models;

class Product
{

    private int $id;
    private string $name;
    private string $description;
    private int $quantity;
    private int $price;
    private int $user_id;
    private array $categories;
    private array $tags;

    public function __construct(int $id, string $name, string $description, int $quantity, int $price, int $user_id, array $categories = [], array $tags = [])
    {

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->user_id = $user_id;
        $this->categories = $categories;
        $this->tags = $tags;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getDescription(): string
    {
        return $this->description;
    }


    public function getQuantity(): int
    {
        return $this->quantity;
    }


    public function getPrice(): int
    {
        return $this->price;
    }


    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}