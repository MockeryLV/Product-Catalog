<?php

namespace App\Repositories\Users;

use App\Repositories\UsersRepository;
use PDO;

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

        $this->connection = new PDO($dsn, $user, $password);
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

                return true;
            }
        }
        return false;

    }

    public function register(array $user): void
    {

        if ($user['password'] === $user['confirmpassword']) {

            $sql = 'INSERT INTO users (username, password, email) VALUES (:username, :password, :email)';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'username' => $user['username'],
                'password' => password_hash($user['password'], PASSWORD_BCRYPT),
                'email' => $user['email']
            ]);

        }

    }
}