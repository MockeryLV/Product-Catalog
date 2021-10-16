<?php
require_once 'vendor/autoload.php';


use App\Repositories\Products\MySqlProductsRepository;


$prod = new MySqlProductsRepository();

var_dump($prod->getAll()->getProducts());