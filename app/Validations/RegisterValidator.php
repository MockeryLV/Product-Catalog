<?php

namespace App\Validations;

class RegisterValidator
{


    private const USERNAME = array(
        'MAX' => 30,
        'MIN' => 4,
        'RESTRICTED_CHARS' => ['/', '@', ';', ',', "'", '/', '\\']
    );

    private const PASSWORD = array(
        'MAX' => 30,
        'MIN' => 3
    );


    private const EMAIL = array(
        'RESTRICTED_CHARS' => ['@', '/', '\\']
    );

    public static function usernameValidate(string $username): bool
    {


        if (strlen($username) > self::USERNAME['MAX'] || strlen($username) < self::USERNAME['MIN']) {
            $_SESSION['errors']['registration']['username'] = 'Username length must be (4-30) symbols!';
            return false;
        }



        foreach (self::USERNAME['RESTRICTED_CHARS'] as $char) {
            if (in_array($char, str_split($username))) {
                $_SESSION['errors']
                ['registration']
                ['username'] = "Username must not contain ("
                    .implode('|',self::USERNAME['RESTRICTED_CHARS'])
                    .")";
                return false;
            }
        }

        unset($_SESSION['errors']['registration']['username']);
        return true;
    }


    public static function passwordValidate(string $password, string $confirm): bool
    {

        if(empty($password)){
            $_SESSION['errors']['registration']['password'] = 'Password must not be empty!';
            return false;
        }
        if ($password !== $confirm) {
            $_SESSION['errors']['registration']['password'] = 'Password and Confirm do not match!';
            return false;
        }
        if (strlen($password) > self::PASSWORD['MAX'] || strlen($password) < self::PASSWORD['MIN']) {
            $_SESSION['errors']['registration']['password'] = 'Password length must be (3-30) symbols!';
            return false;
        }

        unset($_SESSION['errors']['registration']['password']);
        return true;

    }

    public static function emailValidate(string $email): bool
    {

        $email =  explode('@',$email);

        if(count($email) !== 2){
            $_SESSION['errors']['registration']['email'] = 'Invalid email!';
            return false;
        }

        foreach ($email as $item){
            foreach ($item as $i){
                if (in_array($i, self::EMAIL['RESTRICTED_CHARS'])){
                    $_SESSION['errors']['registration']['email'] = "Email must not contain ("
                        .implode('|',self::EMAIL['RESTRICTED_CHARS'])
                        .")";
                    return false;
                }
            }

        }
        unset($_SESSION['errors']['registration']['email']);
        return true;
    }


}