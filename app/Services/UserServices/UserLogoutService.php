<?php


namespace App\Services\UserServices;


use App\Repositories\Users\MySqlUsersRepository;

class UserLogoutService
{

    private MySqlUsersRepository $users;

    public function __construct($container)
    {
        $this->users = $container->get(MySqlUsersRepository::class);
    }

    public function execute(): void
    {

        unset($_SESSION['username']);
        unset($_SESSION['id']);
        header('Location: /home/login');

    }

}