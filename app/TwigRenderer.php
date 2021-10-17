<?php

namespace App;

use Twig\Loader\FilesystemLoader;

class TwigRenderer
{

    public static function render(string $template, array $vars)
    {
        $loader = new FilesystemLoader('app/Views');
        $twig = new \Twig\Environment($loader, []);


        echo $twig->render($template, $vars);
    }


}