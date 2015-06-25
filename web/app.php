<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$env = strtolower(getenv('APP_ENV'));

$loader = ($env !== 'dev') ? require_once __DIR__.'/../app/bootstrap.php.cache' : require_once __DIR__.'/../app/autoload.php';

// Allow debug mode to be enabled independantly of environment.
$debug = 'dev' === $env;
if ($debug) {
    Debug::enable();
}

require_once __DIR__.'/../app/AppKernel.php';

// Load environment based on server environment variable 'APP_ENV'
if ('prod' === $env) {
    $kernel = new AppKernel('prod', $debug);
    $kernel->loadClassCache();
} elseif ('dev' === $env) {
    $kernel = new AppKernel('dev', $debug);
} else {
    $kernel = new AppKernel('test', $debug);
    $kernel->loadClassCache();
}

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
