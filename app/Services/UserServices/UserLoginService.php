<?php


namespace App\Services\UserServices;


use App\Repositories\Users\MySqlUsersRepository;

class UserLoginService{

    private MySqlUsersRepository $users;

    public function __construct($container)
    {
        $this->users = $container->get(MySqlUsersRepository::class);
    }

    public function execute(): void
    {

        $this->users->authenticate($_POST) ? header('Location: /home/products') : header('Location: /home/login');

    }

}