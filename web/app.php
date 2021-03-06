<?php
declare(strict_types=1); // php 7 strict mode
use Symfony\Component\HttpFoundation\Request;

/** @var /Composer\Autoload\ClassLoader */
$loader = require __DIR__.'/../app/autoload.php';

$kernel = new AppKernel('prod', false);
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
