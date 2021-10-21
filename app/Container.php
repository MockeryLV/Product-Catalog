<?php

namespace App;

class Container{


    private array $container = [];



    public function __construct()
    {

    }


    public function add(string $key, $value): void{

        $this->container[$key] = $value;

    }


    public function get(string $key){
        return $this->container[$key];
    }



}