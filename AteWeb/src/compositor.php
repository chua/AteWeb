<?php

// cogemos el punto de entrada al controlador
$compositor = $app['controllers_factory'];

// -- VER LISTADO CALLES -------------------------------------------------------
$compositor->get('/', function () use ($app) {
    return $app['twig']->render('compositor/car_via.html.twig');
})
->bind('comp_car_via');



return $compositor;

?>


