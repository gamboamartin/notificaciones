<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\notificaciones\controllers;
use gamboamartin\administrador\models\adm_usuario;
use gamboamartin\errores\errores;
use gamboamartin\notificaciones\models\not_emisor;

class controlador_adm_usuario extends \gamboamartin\acl\controllers\controlador_adm_usuario {
    final public function recupera_contrasena(bool $header, bool $ws = false)
    {
        $adm_usuario = (new adm_usuario(link: $this->link))->registro(registro_id: $this->registro_id,
            columnas_en_bruto: true, retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener usuario',data:  $adm_usuario, header: $header,ws:  $ws);
        }

        $not_emisores = (new not_emisor(link: $this->link))->registros_activos();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener emisores',data:  $not_emisores, header: $header,ws:  $ws);
        }
        if(count($not_emisores) === 0){
            return $this->retorno_error(mensaje: 'Error no existen emisores',data:  $not_emisores, header: $header,ws:  $ws);
        }

        print_r($not_emisores);exit;

        $not_mensaje_ins = array();
        //$not_mensaje_ins['']

        $email = $adm_usuario->email;
        $usuario = $adm_usuario->user;
        $password = $adm_usuario->password;




    }
}
