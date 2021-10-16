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

    public function __construct(int $id, string $name, string $description, int $quantity, int $price, int $user_id)
    {

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->user_id = $user_id;
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

}