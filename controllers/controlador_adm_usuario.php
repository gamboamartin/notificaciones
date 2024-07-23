<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\notificaciones\controllers;
use config\generales;
use gamboamartin\administrador\models\adm_usuario;
use gamboamartin\errores\errores;
use gamboamartin\notificaciones\models\not_emisor;
use gamboamartin\notificaciones\models\not_mensaje;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\notificaciones\models\not_rel_mensaje;

class controlador_adm_usuario extends \gamboamartin\acl\controllers\controlador_adm_usuario {

    private function mensaje_accesos(string $dom_comercial, string $link_sistema, string $link_web_oficial, $nombre_comercial,
                             string $nombre_completo, string $password, string $usuario): array|string
    {

        $mensaje_html = (new _plantilla())->accesos($dom_comercial, $link_sistema, $link_web_oficial, $nombre_comercial,
            $nombre_completo, $password, $usuario);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al maquetar mensaje',data:  $mensaje_html);
        }

        return $mensaje_html;

    }
    final public function recupera_contrasena(bool $header, bool $ws = false)
    {
        $adm_usuario = (new adm_usuario(link: $this->link))->registro(registro_id: $this->registro_id,
            columnas_en_bruto: true, retorno_obj: true);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener usuario',data:  $adm_usuario, header: $header,ws:  $ws);
        }


        $email = $adm_usuario->email;
        $usuario = $adm_usuario->user;
        $password = $adm_usuario->password;


        $filtro_rec['not_receptor.email'] = $email;
        $existe = (new not_receptor(link: $this->link))->existe(filtro: $filtro_rec);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al validar receptor',data:  $existe, header: $header,ws:  $ws);
        }

        if($existe){
            $not_receptor_id = (new not_receptor(link: $this->link))->not_receptor_id_by_email(email: $email);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error obtener not_receptor_id',data:  $not_receptor_id,
                    header: $header,ws:  $ws);
            }
        }
        else{
            $not_receptor_ins['email'] = $email;
            $r_not_receptor = (new not_receptor(link: $this->link))->alta_registro(registro: $not_receptor_ins);
            if(errores::$error){
                return $this->retorno_error(mensaje: 'Error obtener r_not_receptor',data:  $r_not_receptor,
                    header: $header,ws:  $ws);
            }
            $not_receptor_id = $r_not_receptor->registro_id;
        }

        $not_emisores = (new not_emisor(link: $this->link))->registros_activos();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener emisores',data:  $not_emisores, header: $header,ws:  $ws);
        }

        $n_emisores = count($not_emisores);

        if($n_emisores === 0){
            return $this->retorno_error(mensaje: 'Error no existen emisores',data:  $not_emisores, header: $header,ws:  $ws);
        }

        $emisor_selected = mt_rand(0,$n_emisores-1);
        $not_emisor = (object)$not_emisores[$emisor_selected];

        $generales = new generales();
        $liga = $generales->url_base;
        $link_sistema = "<b>$liga</b>";

        $dom_comercial = '';
        if(isset($generales->dom_comercial)){
            $dom_comercial = $generales->dom_comercial;
        }
        $link_web_oficial = '';
        if(isset($generales->link_web_oficial)){
            $link_web_oficial = $generales->link_web_oficial;
        }
        $nombre_comercial = '';
        if(isset($generales->nombre_comercial)){
            $nombre_comercial = $generales->nombre_comercial;
        }
        $nombre_completo = $adm_usuario->nombre.' '.$adm_usuario->ap.' '.$adm_usuario->am;
        $mensaje = $this->mensaje_accesos(dom_comercial: $dom_comercial,link_sistema:  $link_sistema,
            link_web_oficial:  $link_web_oficial,nombre_comercial:  $nombre_comercial,
            nombre_completo: $nombre_completo,password:  $password,usuario:  $usuario);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar mensaje',data:  $mensaje, header: $header,ws:  $ws);
        }

        $not_mensaje_ins['mensaje'] = $mensaje;
        $not_mensaje_ins['not_emisor_id'] = $not_emisor->not_emisor_id;
        $not_mensaje_ins['asunto'] = 'Recuperacion de contraseña (No Reply)';

        $not_mensaje = (new not_mensaje(link: $this->link))->alta_registro(registro: $not_mensaje_ins);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al insertar mensaje',data:  $not_mensaje, header: $header,ws:  $ws);
        }


        $not_rel_mensaje_ins = array();
        $not_rel_mensaje_ins['not_mensaje_id'] = $not_mensaje->registro_id;
        $not_rel_mensaje_ins['not_receptor_id'] = $not_receptor_id;

        $r_not_rel_mensaje = (new not_rel_mensaje(link: $this->link))->alta_registro(registro: $not_rel_mensaje_ins);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al insertar r_not_rel_mensaje',data:  $r_not_rel_mensaje, header: $header,ws:  $ws);
        }


        $envia_mensaje = (new not_mensaje(link: $this->link))->envia_mensaje(not_mensaje_id: $not_mensaje->registro_id);
        if(errores::$error){

            return $this->retorno_error(mensaje: 'Error al enviar mensaje',data:  $envia_mensaje, header: $header,ws:  $ws);
        }
        $envia_mensaje = (object)$envia_mensaje;
        $envia_mensaje->id_retorno = -1;

        $out = $this->retorno_base(registro_id: $this->registro_id, result: $envia_mensaje, siguiente_view: 'lista', ws: $ws);
        if(errores::$error){
            print_r($out);
            die('Error');
        }

        return $envia_mensaje;

    }
}
