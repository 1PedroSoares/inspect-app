<?php
// Define o diretório público onde o Laravel está
define('LARAVEL_START', microtime(true));

// Carrega o autoloader do Composer
require __DIR__.'/../vendor/autoload.php';

// Carrega o bootstrap do Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// Cria o kernel HTTP e lida com a requisição da API
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);