<?php

namespace App\Controllers;

use App\Repositories\Users\MySqlUsersRepository;
use App\View;

class UserController
{

    private MySqlUsersRepository $users;

    public function __construct()
    {
        $this->users = new MySqlUsersRepository();
    }

    public function login(): View
    {
        return new View('auth/login.view.twig', []);
    }

    public function authenticate(): void
    {

        $this->users->authenticate($_POST) ? header('Location: /home/products') : header('Location: /home/login');

    }

    public function logout(): void
    {

        unset($_SESSION['username']);
        unset($_SESSION['id']);
        header('Location: /home/login');

    }

    public function registration(): View
    {

        return new View('user/user.registration.view.twig', []);

    }

    public function register(): void
    {

        if($this->users->register($_POST));

        header('Location: /home/login');
    }

}
