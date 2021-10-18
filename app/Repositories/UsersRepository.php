<?php

namespace App\Repositories;

interface UsersRepository
{

    public function authenticate(array $loginData);

    public function register(array $user);
}