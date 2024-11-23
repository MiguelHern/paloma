<?php

use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Crear el contenedor de dependencias
$container = new Container();
AppFactory::setContainer($container);

// Crear la aplicación Slim
$app = AppFactory::create();

// Cargar configuraciones
$settings = require __DIR__ . '/../config/settings.php';
foreach ($settings as $key => $value) {
    $container->set($key, $value);
}

// Configurar la conexión a la base de datos
$container->set('db', function () use ($settings) {
    $dbSettings = $settings['settings']['db']; // Asegúrate de que los detalles de la DB estén en `settings.php`
    $dsn = "mysql:host={$dbSettings['host']};dbname={$dbSettings['dbname']};port={$dbSettings['port']}";
    try {
        $pdo = new PDO($dsn, $dbSettings['username'], $dbSettings['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error en la conexión a la base de datos: " . $e->getMessage());
    }
});

// Configurar Twig
$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);

// Agregar el middleware de Twig
$app->add(new TwigMiddleware($twig, $app->getRouteCollector()->getRouteParser(), $app->getBasePath()));

// Middleware de errores
$app->addErrorMiddleware(
    $settings['settings']['displayErrorDetails'],
    $settings['settings']['logErrors'],
    $settings['settings']['logErrorDetails']
);

// Servir archivos estáticos desde la carpeta public
$app->get('/public/{file:.+}', function ($request, $response, $args) {
    $file = __DIR__ . '/../public/' . $args['file'];
    if (file_exists($file)) {
        $response->getBody()->write(file_get_contents($file));
        return $response->withHeader('Content-Type', mime_content_type($file));
    }
    return $response->withStatus(404);
});



// Cargar rutas
require __DIR__ . '/../src/routes.php';

// Ejecutar la aplicación
$app->run();
