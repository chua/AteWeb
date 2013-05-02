lo<?php

// Controladores relacionados con la parte de administración del sitio web
$croquis = $app['controllers_factory'];

// -- VER LISTADO CALLES -------------------------------------------------------
$croquis->get('/', function () use ($app) {
    return $app['twig']->render('croquis/croquis.html.twig');
})
->bind('croquis');




// -- VER CALLE DETERMINADA ----------------------------------------------------
$croquis->get('/ver/{id}', function ($id) use ($app) {
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
        //son para descargar
        $cdr[$i] = (file_exists($ruta."$i.cdr")) ? "/data/$id/$i.cdr" : false;
        $jpg[$i] = (file_exists($ruta."$i.jpg")) ? "/data/$id/$i.jpg" : false;
        //son para abrir
        $cvi[$i] = (file_exists($ruta."$i.cvi")) ? file_get_contents($ruta.$i.".cvi") : false;
        $dpc[$i] = (file_exists($ruta."$i.dpc")) ? file_get_contents($ruta.$i.".dpc") : false;
        $svh[$i] = (file_exists($ruta."$i.svh")) ? file_get_contents($ruta.$i.".svh") : false;
        $aut[$i] = (file_exists($ruta."$i.aut")) ? file_get_contents($ruta.$i.".aut") : false;
        $not[$i] = (file_exists($ruta."$i.not")) ? file_get_contents($ruta.$i.".not") : false;
        //continua si alguno es cierto. si todos son falsos, no queda ninguno
        //no pueden haber huecos en la numeración.
    } while (($cdr[$i]) or ($jpg[$i]) or ($cvi[$i]) or ($dpc[$i]) or ($svh[$i]));
               
    return $app['twig']->render('croquis/ver_croquis.html.twig',array(
                                                    'cdr' => $cdr,
                                                    'jpg' => $jpg, 
                                                    'cvi' => $cvi,
                                                    'dpc' => $dpc,
                                                    'svh' => $svh,
                                                    'cnt' => --$i,
                                                    'cod' => $id,
                                                    'aut' => $aut,
                                                    'not' => $not,
                                                    'nom' => $nom));
})
->bind('ver_croquis');

return $croquis;