<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

$loader = require dirname(__DIR__).'/app/autoload.php';

if ($debug = filter_var($debug, FILTER_VALIDATE_BOOLEAN)) {
    Debug::enable();
} else {
    include dirname(__DIR__).'/var/bootstrap.php.cache';
}

$kernel = new AppKernel(getenv('SYMFONY_ENV'), $debug);
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
