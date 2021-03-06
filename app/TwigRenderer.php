<?php

namespace App;

use Twig\Loader\FilesystemLoader;

class TwigRenderer
{

    public static function render(string $template, array $vars)
    {
        $loader = new FilesystemLoader('app/Views');
        $twig = new \Twig\Environment($loader, []);
        $twig->addGlobal('username', $_SESSION['username']);
        $twig->addGlobal('id', $_SESSION['id']);
        $twig->addGlobal('errors', $_SESSION['errors']);
        $twig->addGlobal('get', $_GET);

        echo $twig->render($template, $vars);
    }


}