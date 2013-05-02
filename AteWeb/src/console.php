<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('AtElx', 'beta1');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console
    ->register('genera_calles')
    ->setDefinition(array(
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('Recorre el directorio de datos en busca de las vías que componen
        los puntos de interés que hay en ellas, para generar un bloque con todas ellas, 
        acelerando el funcionamiento de la web')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        //configuración de la tarea
        $rutaInicio = $app['data']['archivo']; 
        $nombreFicheroEntrada = "calles.dat";
        $nombreFicheroSalida  = "../../templates/croquis/croquis.data.html";
        $nombreEtiqueta = "li";
        
        //inicializaciones
        $cadOut ="";
        $cTot=0;
        $cOk =0;
        if ($app['debug']) $output->writeln("Buscando en ".$rutaInicio);
        //abro la carpeta origen
        if ($dir = opendir($rutaInicio)) {
            //recorro todos los ficheros en busca de directorios, descartando los del sistema
            if ($app['debug']) $output->write("-> [");
            while ($handle = readdir($dir)){
                 //descarto directorios del sistema (.),(..),(.nombre)
                if ($handle[0] != ".") {
                    if (is_dir($rutaInicio.$handle)){
                        if (file_exists($rutaInicio.$handle."/".$nombreFicheroEntrada)){
                            if ($line = file_get_contents($rutaInicio.$handle."/".$nombreFicheroEntrada)){
                                if ($app['debug']) $output->write(".");
                            }else{
                                if ($app['debug']) $output->write("!");
                            }
                            $line = strtolower(($line));
                          // $line = str_replace(",",  "$nbsp; # $nbsp;  ",$line);   
                            $cadOut.= "<$nombreEtiqueta><a href=\"{{path('ver_croquis',{'id':'$handle'})}}\">$line</a></$nombreEtiqueta>";
                            //  $cadOut.= "<$nombreEtiqueta><a href=\"$rutaServer$handle/d.html\">$line</a></$nombreEtiqueta>";
                            $cOk++;
                        } // fin file exists
                        $cTot++; //acumulo que visito un directorio.
                    }//fin is_dir
                }//fin if !sysdir 
            } //fin while
            if ($app['debug']) $output->writeln("]");

            //guardo todo el contenido
           // $cadOud = strtolower($cadOut);  //utf8_encode($cadOut);   
            file_put_contents($rutaInicio.$nombreFicheroSalida, $cadOut);
            if ($app['debug']) $output->writeln("Grabado en $rutaInicio$nombreFicheroSalida");
            $output->writeln("OK. $cOk directorio/s válido/s. Leido/s $cTot");

        } //if opendir
        else {
            $output->writeln("Abortado. No se puede abrir $rutaInicio");
        }
    
    }) //fin setCode
;

return $console;
