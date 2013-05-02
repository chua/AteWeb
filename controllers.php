<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//montamos el backend sobre admin
// -- BACKEND ------------------------------------------------------------------
//$app->mount('/admin', include 'backend.php');


// -- INICIO -------------------------------------------------------------------
$app->get('/', function () use ($app) {
    //$cadIn = file_get_contents("../data/calles.data");, array('calles' => $cadIn)
    return $app['twig']->render('index.html.twig',array('fix'=>$app['fixweb']));
})
->bind('home');



// -- VER LISTADO CALLES -------------------------------------------------------
$app->get('/calles', function () use ($app) {
   // $cadIn = file_get_contents("../data/calles.data");
    return $app['twig']->render('calles.html.twig',array('fix'=>$app['fixweb']));
})
->bind('calles');




// -- VER CALLE DETERMINADA ----------------------------------------------------
$app->get('/calles/ver/{id}', function ($id) use ($app) {
    //ruta de los directorios de datos
    $ruta = $app['data']['archivo']."$id/";
    //leemos el nombre de la calle (debe existir siempre)
    $nom = (file_exists($ruta."calles.dat")) ? file_get_contents($ruta."calles.dat") : false;
    //iniciamos todos los arrays
    $cdr = $jpg = $cvi = $dpc = $svh = array();
    //iniciamos el contador
    $i = 0;
    //creamos el array con los datos que si existen.
    // ruta/numero_objeto.extensión
    do {
        $i++;
        $cdr[$i] = (file_exists($ruta."$i.cdr")) ? "/data/$id/$i.cdr" : false;
        $jpg[$i] = (file_exists($ruta."$i.jpg")) ? "/data/$id/$i.jpg" : false;
        $cvi[$i] = (file_exists($ruta."$i.cvi")) ? file_get_contents($ruta.$i.".cvi") : false;
        $dpc[$i] = (file_exists($ruta."$i.dpc")) ? file_get_contents($ruta.$i.".dpc") : false;
        $svh[$i] = (file_exists($ruta."$i.svh")) ? file_get_contents($ruta.$i.".svh") : false;
        //continua si alguno es cierto. si todos son falsos, no queda ninguno
        //no pueden haber huecos en la numeración.
    } while (($cdr[$i]) or ($jpg[$i]) or ($cvi[$i]) or ($dpc[$i]) or ($svh[$i]));
            
    return $app['twig']->render('ver_calle.html.twig',array('fix'=>$app['fixweb'],
							 'cdr' => $cdr,
                                                         'jpg' => $jpg, 
                                                         'cvi' => $cvi,
                                                         'dpc' => $dpc,
                                                         'svh' => $svh,
                                                         'cnt' => --$i,
                                                         'cod' => $id,
                                                         'nom' => $nom));
})
->bind('ver_calle');



$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
