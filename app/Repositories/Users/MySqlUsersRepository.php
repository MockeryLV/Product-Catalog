<?php

namespace App\Repositories\Users;

use App\Repositories\UsersRepository;
use App\Validations\RegisterValidator;
use PDO;
use PDOException;

class MySqlUsersRepository implements UsersRepository
{

    private PDO $connection;

    public function __construct()
    {
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $dbname = 'products_catalog';

        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;

        try{
            $this->connection = new PDO($dsn, $user, $password);
        }catch(PDOException $e){
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function authenticate(array $loginData): bool
    {

        $sql = 'SELECT * FROM users WHERE username=:username';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'username' => $loginData['username'],
        ]);

        $user = [];

        foreach ($stmt->fetch(PDO::FETCH_ASSOC) as $key => $row) {
            $user[$key] = $row;
        }

        if (password_verify($loginData['password'], $user['password'])) {
            $id = $user['id'];
            $username = $user['username'];

            if ($username) {
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                unset($_SESSION['errors']['registration']);
                unset($_SESSION['errors']['loginFail']);
                return true;
            }
        }

        $_SESSION['errors']['loginFail'] = 'Username or/and password not correct!';


        return false;

    }

    public function register(array $user): bool
    {

        if (
            RegisterValidator::usernameValidate(
                $user['username']
            )
            && RegisterValidator::passwordValidate(
                $user['password'],
                $user['confirmpassword'])
            && RegisterValidator::emailValidate(
                $user['email']
            )
        ) {

                $sql = 'INSERT INTO users (username, password, email) VALUES (:username, :password, :email)';
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([
                    'username' => $user['username'],
                    'password' => password_hash($user['password'], PASSWORD_BCRYPT),
                    'email' => $user['email']
                ]);
                return true;

        } else {

            header('Location: /home/registration');
        }


    }
}