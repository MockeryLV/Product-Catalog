<?php


namespace App\Services\UserServices;


use App\Repositories\Users\MySqlUsersRepository;
use App\View;

class UserRegistrationFormService
{

    private MySqlUsersRepository $users;

    public function __construct($container)
    {
        $this->users = $container->get(MySqlUsersRepository::class);
    }

    public function execute(): View
    {

        return new View('user/user.registration.view.twig', []);

    }

}