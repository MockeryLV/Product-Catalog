<?php

namespace App\Middlewares;


use App\Auth;
use App\Controllers\UserController;

class AuthorizedMiddleware implements Middleware {



    public function handle(): void{

        if(!Auth::loggedIn()){
            header('Location: /home/login');
            exit;
        }

    }


}