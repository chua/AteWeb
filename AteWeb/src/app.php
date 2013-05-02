<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SecurityServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new SecurityServiceProvider());
$app->register(new DoctrineServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
));
/*
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));
*/


$app['security.firewalls'] = array(
    'unsecured' => array(
        'pattern' => '^/$',
      'anonymous' => true,
    ),
    'secured' => array(
        'pattern' => '^.*$',
        'http'    => true,
        'logout' => array('logout_path' => '/croquis/logout'),
        'users'   => array(
            // la contraseña sin codificar es "1234"
            'admin'     => array('ROLE_USER',   '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
    //        'atestados' => array('ROLE_USER_AT', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
        ),
    ),
    
);



return $app;
