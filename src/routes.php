<?php

global $app;

use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Controllers\Admin\HomeController as AdminHomeController;

// Configurar Twig manualmente
$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);

// Agregar TwigMiddleware
$app->add(new TwigMiddleware($twig, $app->getRouteCollector()->getRouteParser(), $app->getBasePath()));

// Rutas
$app->get('/admin', [new AdminHomeController($twig), 'index']);

$app->get('/public/{path:.*}', function ($request, $response, $args) {
    $path = $args['path'];
    $file = __DIR__ . "/../public/" . $path;

    if (file_exists($file)) {
        return $response->withHeader('Content-Type', mime_content_type($file))
            ->write(file_get_contents($file));
    }

    return $response->withStatus(404)->write('File not found');
});
