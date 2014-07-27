<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;

//prepare routing
$locator = new FileLocator(array(__DIR__ . '/../app'));
$loader = new YamlFileLoader($locator);

/* @var $routeCollection RouteCollection */
$routeCollection = $loader->load('routing.yml');

$request = Request::createFromGlobals();

$matcher = new UrlMatcher($routeCollection, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher));

$resolver = new ControllerResolver();
$kernel = new HttpKernel($dispatcher, $resolver);

/* @var $response Response */
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
