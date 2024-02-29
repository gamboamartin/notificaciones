<?php
namespace gamboamartin\notificaciones\instalacion;

use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion
{

    private function not_adjunto(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_mensaje_id'] = new stdClass();
        $foraneas['doc_documento_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $campos = new stdClass();

        $campos->name_out = new stdClass();
        $campos->name_out->default = 'SN';


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }

        $result = new stdClass();

        $result->campos_r = $campos_r;

        return $result;

    }

    private function not_emisor(PDO $link): array|stdClass
    {
        $result = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }
        $result->create = $create;

        $campos = new stdClass();

        $campos->email = new stdClass();
        $campos->user_name = new stdClass();
        $campos->password =new stdClass();
        $campos->port =new stdClass();
        $campos->host =new stdClass();
        $campos->smtp_secure =new stdClass();


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }



        $result->campos_r = $campos_r;


        return $result;

    }
    private function not_mensaje(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_emisor_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $campos = new stdClass();

        $campos->asunto = new stdClass();
        $campos->mensaje = new stdClass();
        $campos->mensaje->default = 'SIN MENSAJE';


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }

        $result = new stdClass();
        $result->foranenas = $foraneas_r;
        $result->campos = $campos_r;

        return $result;

    }

    private function not_mensaje_etapa(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_mensaje_id'] = new stdClass();
        $foraneas['pr_etapa_proceso_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $campos = new stdClass();

        $campos->fecha = new stdClass();
        $campos->fecha->tipo_dato = 'DATE';
        $campos->fecha->default = '1900-01-01';


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }

        $result = new stdClass();
        $result->foranenas = $foraneas_r;
        $result->campos = $campos_r;

        return $result;

    }

    private function not_receptor(PDO $link): array|stdClass
    {
        $result = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }
        $result->create = $create;

        $campos = new stdClass();

        $campos->email = new stdClass();


        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }



        $result->campos_r = $campos_r;


        return $result;

    }
    private function not_rel_mensaje(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_mensaje_id'] = new stdClass();
        $foraneas['not_receptor_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }

        $result = new stdClass();
        $result->foranenas = $foraneas_r;


        return $result;

    }
    private function not_rel_mensaje_etapa(PDO $link): array|stdClass
    {
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: __FUNCTION__);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al create', data:  $create);
        }

        $foraneas = array();
        $foraneas['not_rel_mensaje_id'] = new stdClass();
        $foraneas['pr_etapa_proceso_id'] = new stdClass();


        $foraneas_r = $init->foraneas(foraneas: $foraneas,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $foraneas_r);
        }


        $campos = new stdClass();

        $campos->fecha = new stdClass();
        $campos->fecha->tipo_dato = 'DATE';
        $campos->fecha->default = '1900-01-01';

        $campos_r = $init->add_columns(campos: $campos,table:  __FUNCTION__);

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $campos_r);
        }

        $result = new stdClass();
        $result->foranenas = $foraneas_r;
        $result->campos = $campos_r;

        return $result;

    }

    final public function instala(PDO $link): array|stdClass
    {

        $result = new stdClass();

        $not_emisor = $this->not_emisor(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_emisor', data:  $not_emisor);
        }

        $result->not_emisor = $not_emisor;

        $not_receptor = $this->not_receptor(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_receptor', data:  $not_receptor);
        }

        $result->not_emisor = $not_emisor;

        $not_mensaje = $this->not_mensaje(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_mensaje', data:  $not_mensaje);
        }

        $result->not_mensaje = $not_mensaje;


        $not_adjunto = $this->not_adjunto(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_adjunto', data:  $not_adjunto);
        }

        $result->not_adjunto = $not_adjunto;


        $not_mensaje_etapa = $this->not_mensaje_etapa(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_mensaje_etapa', data:  $not_mensaje_etapa);
        }

        $result->not_mensaje = $not_mensaje;

        $not_mensaje = $this->not_rel_mensaje(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar not_mensaje', data:  $not_mensaje);
        }

        $result->not_mensaje = $not_mensaje;


        $not_rel_mensaje_etapa = $this->not_rel_mensaje_etapa(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar fc_factura', data:  $not_rel_mensaje_etapa);
        }

        $result->not_rel_mensaje_etapa = $not_rel_mensaje_etapa;


        return $result;

    }

}
