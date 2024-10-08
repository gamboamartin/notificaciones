<?php
namespace gamboamartin\notificaciones\models;

use base\orm\_modelo_parent_sin_codigo;

use base\orm\modelo;
use gamboamartin\errores\errores;
use gamboamartin\notificaciones\mail\_mail;
use gamboamartin\proceso\models\pr_proceso;
use PDO;
use stdClass;

class not_rel_mensaje extends _modelo_parent_sin_codigo {

    private modelo $modelo_etapa;
    public function __construct(PDO $link){
        $tabla = 'not_rel_mensaje';
        $columnas = array($tabla=>false,'not_mensaje'=>$tabla,'not_receptor'=>$tabla,'not_emisor'=>'not_mensaje');
        $campos_obligatorios = array('not_mensaje_id','not_receptor_id');

        $no_duplicados = array();

        $campos_view = array();
        $columnas_extra = array();

        $not_rel_mensaje_etapa = "(SELECT pr_etapa.descripcion FROM pr_etapa 
            LEFT JOIN pr_etapa_proceso ON pr_etapa_proceso.pr_etapa_id = pr_etapa.id 
            LEFT JOIN not_rel_mensaje_etapa ON not_rel_mensaje_etapa.pr_etapa_proceso_id = pr_etapa_proceso.id
            WHERE not_rel_mensaje_etapa.not_rel_mensaje_id = not_rel_mensaje.id ORDER BY not_rel_mensaje_etapa.id DESC LIMIT 1)";


        $columnas_extra['not_rel_mensaje_etapa'] = "$not_rel_mensaje_etapa";

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view, columnas_extra: $columnas_extra,
            no_duplicados: $no_duplicados, tipo_campos: array());

        $this->NAMESPACE = __NAMESPACE__;

        $this->etiqueta = 'Mensajes';

        $this->modelo_etapa = new not_rel_mensaje_etapa(link: $this->link);
    }

    public function alta_bd(array $keys_integra_ds = array('descripcion')): array|stdClass
    {
        $not_mensaje = (new not_mensaje(link: $this->link))->registro(registro_id: $this->registro['not_mensaje_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener not_mensaje',data: $not_mensaje);
        }

        $not_receptor = (new not_receptor(link: $this->link))->registro(registro_id: $this->registro['not_receptor_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener not_receptor',data: $not_receptor);
        }

        if(!isset($this->registro['descripcion'])){
            $descripcion = $not_mensaje['not_emisor_email'].' '.$not_mensaje['not_mensaje_asunto'].' '.
                $not_receptor['not_receptor_email'].' '.date('YmdHis');
            $this->registro['descripcion'] = $descripcion;
        }
        $r_alta_bd = parent::alta_bd(keys_integra_ds: $keys_integra_ds); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al dar de alta mensaje',data: $r_alta_bd);
        }

        $r_alta_not_rel_mensaje_etapa = (new pr_proceso(link: $this->link))->inserta_etapa(adm_accion: __FUNCTION__, fecha: '',
            modelo: $this, modelo_etapa: $this->modelo_etapa, registro_id: $r_alta_bd->registro_id, valida_existencia_etapa: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar etapa', data: $r_alta_not_rel_mensaje_etapa);
        }

        return $r_alta_bd;
    }

    final public function envia_mensaje(int $not_rel_mensaje_id, array $cc = array(), array $cco = array()){
        $not_rel_mensaje = $this->registro(registro_id: $not_rel_mensaje_id, retorno_obj: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener mensaje',data:  $not_rel_mensaje);
        }


        /**
         * REFACTORIZAR CON ATTR EN DATABASE
         */
        if($not_rel_mensaje->not_rel_mensaje_etapa=== 'ENVIADO'){
            return $this->error->error(mensaje: 'Error el mensaje ha sido enviado',data:  $not_rel_mensaje);
        }

        $filtro['not_mensaje.id'] = $not_rel_mensaje->not_mensaje_id;
        $r_not_adjunto = (new not_adjunto(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener adjuntos', data: $r_not_adjunto);
        }

        $adjuntos = $r_not_adjunto->registros;

        $mail = (new _mail())->envia(mensaje: $not_rel_mensaje, adjuntos: $adjuntos,cc: $cc, cco: $cco);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al enviar mensaje',data:  $mail);
        }

        $r_alta_not_rel_mensaje_etapa = (new pr_proceso(link: $this->link))->inserta_etapa(adm_accion: __FUNCTION__, fecha: '',
            modelo: $this, modelo_etapa: $this->modelo_etapa, registro_id: $not_rel_mensaje_id, valida_existencia_etapa: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar etapa', data: $r_alta_not_rel_mensaje_etapa);
        }

        return $mail;
    }


}