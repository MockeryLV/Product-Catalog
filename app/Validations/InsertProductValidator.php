<?php


namespace App\Validations;

use App\Repositories\Categories\MySqlCategoriesRepository;
use App\Repositories\Tags\MySqlTagsRepository;

class InsertProductValidator{


    const NAME = [
        'MAX' => 50,
        'MIN' => 3,
        'RESTRICTED_CHARS' =>  ['/', '@', ';', ',', "'", '/', '\\']
    ];



    public static function nameValidate(string $name): bool{


            if(strlen($name) > self::NAME['MAX'] || strlen($name) < self::NAME['MIN']){
                $_SESSION['errors']['createProduct']['name'] = "Product's name must be (3-50) chars long!";
                return false;
            }

        foreach (self::NAME['RESTRICTED_CHARS'] as $char) {
            if (in_array($char, str_split($name))) {
                $_SESSION['errors']
                ['createProduct']
                ['name'] = "Product's name must not contain ("
                    .implode('|',self::NAME['RESTRICTED_CHARS'])
                    .")";
                return false;
            }
        }

            unset($_SESSION['errors']['createProduct']['name']);
            return true;

    }

    public static function categoryValidate(array $categories): bool{



        if(count($categories) <= 1 && $categories[0] === ''){
            $_SESSION['errors']['createProduct']['category'] = "You should select at least 1 category";
            return false;
        }


        $categoriesAll = (new MySqlCategoriesRepository())
            ->getAllCategories()
            ->getCategories();

        foreach ($categoriesAll as $key => $category){
            $categoriesAll[$key] = $category->getCategory();
        }


        foreach ($categories as $category){
            if(!in_array($category, $categoriesAll) && $category !== ''){
                $_SESSION['errors']['createProduct']['category'] = "Please select correct categories!";
                return false;
            }
        }

        unset($_SESSION['errors']['createProduct']['category']);
        return true;
    }

    public static function descriptionValidate(string $description): bool{

        if(empty($description)){
            $_SESSION['errors']['createProduct']['description'] = "Please add description!";
            return false;
        }

        unset($_SESSION['errors']['createProduct']['description']);

        return true;
    }

    public static function priceValidate(int $price){

        if(empty($price)){
            $_SESSION['errors']['createProduct']['price'] = "Please add price!";
            return false;
        }

        if($price <= 0){
            $_SESSION['errors']['createProduct']['price'] = "Price should not be less or equal to 0!";
            return false;
        }

        unset($_SESSION['errors']['createProduct']['price']);
        return true;

    }

    public static function quantityValidate(int $quantity): bool{

        if(empty($quantity)){
            $_SESSION['errors']['createProduct']['quantity'] = "You should add quantity!";
            return false;
        }

        if($quantity <= 0){
            $_SESSION['errors']['createProduct']['quantity'] = "Quantity should be more than 0!";
            return false;
        }

        unset($_SESSION['errors']['createProduct']['quantity']);
        return true;
    }

    public static function tagsValidate(array $tags): bool{



        $tagsAll = (new MySqlTagsRepository())
            ->getAllTag()
            ->getTags();

        foreach ($tagsAll as $key => $tag){
            $tagsAll[$key] = $tag->getTag();
        }

        foreach ($tags as $tag){
            if(!in_array($tag, $tagsAll) && $tag != ""){
                $_SESSION['errors']['createProduct']['tags'] = "Please select correct tags or do not select at all!";
                return false;
            }
        }

        unset($_SESSION['errors']['createProduct']['tags']);
        return true;

    }

}