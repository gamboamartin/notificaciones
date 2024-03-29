<?php

use base\conexion;
use gamboamartin\errores\errores;

$_SESSION['usuario_id'] = 2;

require "init.php";
require 'vendor/autoload.php';

$con = new conexion();
$link = conexion::$link;

$link->beginTransaction();


$documento = new gamboamartin\documento\instalacion\instalacion();

$instala = $documento->instala(link: $link);
if(errores::$error){
    $link->rollBack();
    $error = (new errores())->error(mensaje: 'Error al instalar documento', data: $instala);
    print_r($error);
    exit;
}

print_r($instala);


$notificaciones = new gamboamartin\notificaciones\instalacion\instalacion();

$instala = $notificaciones->instala(link: $link);
if(errores::$error){
    $link->rollBack();
    $error = (new errores())->error(mensaje: 'Error al instalar notificaciones', data: $instala);
    print_r($error);
    exit;
}

print_r($instala);

$link->commit();


