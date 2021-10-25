<?php


namespace App\Services\UserServices;


use App\Repositories\Users\MySqlUsersRepository;
use App\View;

class LoginFromService{

    private MySqlUsersRepository $users;

    public function __construct($container)
    {
        $this->users = $container->get(MySqlUsersRepository::class);
    }

    public function execute(): View
    {

        return new View('auth/login.view.twig', []);

    }

}