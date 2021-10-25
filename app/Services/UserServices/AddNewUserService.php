<?php


namespace App\Services\UserServices;


use App\Repositories\Users\MySqlUsersRepository;
use App\View;

class AddNewUserService
{

    private MySqlUsersRepository $users;

    public function __construct($container)
    {
        $this->users = $container->get(MySqlUsersRepository::class);
    }

    public function execute(): void
    {


        if($this->users->register($_POST)){
            header('Location: /home/login');
        }else{
            header('Location: /home/registration');
        }

    }

}