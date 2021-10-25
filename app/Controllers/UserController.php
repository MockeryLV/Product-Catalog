<?php

namespace App\Controllers;

use App\Container;
use App\Repositories\Users\MySqlUsersRepository;
use App\Services\UserServices\AddNewUserService;
use App\Services\UserServices\LoginFromService;
use App\Services\UserServices\UserLoginService;
use App\Services\UserServices\UserLogoutService;
use App\Services\UserServices\UserRegistrationFormService;
use App\View;

class UserController
{

    private MySqlUsersRepository $users;

    private UserLoginService $userLoginService;
    private UserRegistrationFormService $userRegistrationFormService;
    private AddNewUserService $addNewUserService;


    public function __construct(Container $container)
    {
        $this->users = $container->get(MySqlUsersRepository::class);
        $this->userLoginService = new UserLoginService($container);
        $this->userLogoutService = new UserLogoutService($container);
        $this->userRegistrationFormService =  new UserRegistrationFormService($container);
        $this->addNewUserService = new AddNewUserService($container);
        $this->loginFormService = new LoginFromService($container);
    }

    public function login(): View
    {
        $response = $this->loginFormService->execute();

        return $response;

    }

    public function authenticate(): void
    {

        $this->userLoginService->execute();

    }

    public function logout(): void
    {

        $this->userLogoutService->execute();

    }

    public function registration(): View
    {

        $response = $this->userRegistrationFormService->execute();

        return $response;
    }

    public function register(): void
    {

        $this->addNewUserService->execute();

    }

}
