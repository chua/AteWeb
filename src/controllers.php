<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//montamos el backend sobre admin
//va el primero porque tiene definidas las restricciones de seguridad (acceso a 
//admin, por eso es la primera ruta definida.)
// -- BACKEND ------------------------------------------------------------------
//$app->mount('/admin', include 'admin.php');

// -- INICIO -------------------------------------------------------------------
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
})
->bind('home');

//montamos las calles (menu croquis)
// -- CALLES ------------------------------------------------------------------
$app->mount('/croquis', include 'croquis.php');


//montamos el compositor (menu compositor)
// -- CALLES ------------------------------------------------------------------
$app->mount('/compositor', include 'compositor.php');


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
