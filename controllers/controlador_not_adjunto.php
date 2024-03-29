<?php
/**
 * @author Kevin Acuña Vega
 * @version 1.0.0
 * @created 2022-07-07
 * @final En proceso
 *
 */
namespace gamboamartin\notificaciones\controllers;


use gamboamartin\errores\errores;
use gamboamartin\notificaciones\html\not_adjunto_html;
use gamboamartin\notificaciones\html\not_mensaje_html;
use gamboamartin\notificaciones\models\not_adjunto;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;
use gamboamartin\template\html;

use html\doc_documento_html;
use PDO;
use stdClass;

class controlador_not_adjunto extends _ctl_parent_sin_codigo {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new not_adjunto(link: $link);
        $html_ = new not_adjunto_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);

        $datatables = $this->init_datatable();
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable',data: $datatables);
            print_r($error);
            die('Error');
        }

        parent::__construct(html: $html_, link: $link, modelo: $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->titulo_lista = 'Emisores';
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = parent::alta(header: $header,ws:  $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:  $ws);
        }

        $this->inputs = new stdClass();


        $not_mensaje_id = (new not_mensaje_html(html: $this->html_base))->select_not_mensaje_id(cols: 12,
            con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar input',data:  $not_mensaje_id, header: $header,ws:  $ws);
        }

        $this->inputs->not_mensaje_id = $not_mensaje_id;

        $doc_documento_id = (new doc_documento_html(html: $this->html_base))->select_doc_documento_id(cols: 12,
            con_registros: true,id_selected: -1,link:  $this->link);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar input',data:  $not_mensaje_id, header: $header,ws:  $ws);
        }

        $this->inputs->doc_documento_id = $doc_documento_id;


        return $r_alta;
    }


    /**
     * Initializes and returns a DataTable object.
     *
     * @return stdClass The created DataTable object with properties columns and filtro.
     */
    final public function init_datatable(): stdClass
    {

        $columns["not_adjunto_id"]["titulo"] = "Id";
        $columns["not_emisor_email"]["titulo"] = "Email Emisor";
        $columns["not_mensaje_asunto"]["titulo"] = "Asunto";

        $filtro[] = array("not_adjunto.id");
        $filtro[] = array("not_emisor.email");
        $filtro[] = array("not_mensaje.asunto");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    public function modifica(bool $header, bool $ws = false, array $keys_selects = array()): array|stdClass
    {
        $r_modifica = parent::modifica($header, $ws, $keys_selects); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:  $ws);
        }
        $this->inputs = new stdClass();

        $not_mensaje_id = (new not_mensaje_html(html: $this->html_base))->select_not_mensaje_id(cols: 12,
            con_registros: true,id_selected: $this->row_upd->not_mensaje_id,link:  $this->link);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar input',data:  $not_mensaje_id, header: $header,ws:  $ws);
        }

        $this->inputs->not_mensaje_id = $not_mensaje_id;

        $doc_documento_id = (new doc_documento_html(html: $this->html_base))->select_doc_documento_id(cols: 12,
            con_registros: true,id_selected: $this->row_upd->doc_documento_id,link:  $this->link);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar input',data:  $doc_documento_id, header: $header,ws:  $ws);
        }

        $this->inputs->doc_documento_id = $doc_documento_id;


        return $r_modifica;


    }


}