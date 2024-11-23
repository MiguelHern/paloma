<?php

namespace App\Controllers\Admin;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    protected $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function index(Request $request, Response $response): Response
    {
        // Renderiza la vista `admin/home.twig`
        return $this->twig->render($response, 'admin/home.twig', [
            'title' => 'Admin Home',
            'message' => '¡Bienvenido al Panel de Administración!',
        ]);
    }
}
