<?php
namespace gamboamartin\notificaciones\controllers;

use gamboamartin\errores\errores;
use stdClass;

class _plantilla{
    final public function accesos(string $dom_comercial, string $link_acceso, string $link_web_oficial,
                                  string $nombre_comercial, string $nombre_completo, string $password, string $usuario): array|string
    {


        $base = $this->base_correo_acceso($dom_comercial, $link_acceso, $link_web_oficial, $nombre_comercial, $password, $usuario);
        if(errores::$error){
            return (new errores())->error('Error al generar base',data: $base);
        }

        return "Buen día <b>$nombre_completo</b>: Estos son tus accesos: <br>$base->accesos<br> $base->pie<br> $base->estilo";
        
    }

    private function accesos_html(string $link_acceso, string $password, string $usuario): string|array
    {
        $link_acceso = trim($link_acceso);
        if($link_acceso === ''){
            return (new errores())->error('Error al generar link_acceso',data: $link_acceso);
        }
        $usuario = trim($usuario);
        if($usuario === ''){
            return (new errores())->error('Error al generar usuario',data: $usuario);
        }
        $password = trim($password);
        if($password === ''){
            return (new errores())->error('Error al generar password',data: $password);
        }

        $el_a = "<br><b>$link_acceso</b><br>";
        $el_user = "<br><b>Usuario:</b> $usuario<br>";
        $el_pass = "<br><b>Password:</b> $password<br>";
        return $el_a.$el_user.$el_pass;
    }

    private function base_correo_acceso(string $dom_comercial, string $link_acceso, string $link_web_oficial,
                                        string $nombre_comercial, string $password, string $usuario): array|stdClass
    {
        $pie = $this->pie(dom_comercial: $dom_comercial,link_web_oficial:  $link_web_oficial,nombre_comercial: $nombre_comercial);
        if(errores::$error){
            return (new errores())->error('Error al generar pie',data: $pie);
        }

        $estilo = $this->estilo_correo();
        if(errores::$error){
            return (new errores())->error('Error al generar estilo',data: $estilo);
        }
        $accesos = $this->accesos_html($link_acceso, $password, $usuario);
        if(errores::$error){
            return (new errores())->error('Error al generar estilo',data: $accesos);
        }

        $html = new stdClass();
        $html->pie = $pie;
        $html->estilo = $estilo;
        $html->accesos = $accesos;

        return $html;


    }

    final public function bienvenida(string $dom_comercial, string $link_acceso, string $link_web_oficial, string $nombre_comercial, string $password, string $usuario)
    {
        $base = $this->base_correo_acceso($dom_comercial, $link_acceso, $link_web_oficial, $nombre_comercial, $password, $usuario);
        if(errores::$error){
            return (new errores())->error('Error al generar base',data: $base);
        }

        $html = "<p>Estimado cliente: </p>
                    <p>Reciba un cordial saludo, el presente documento es para poder dar inicio al proceso de implementación. </p>
                    <p>Por lo anterior, requerimos la siguiente documentación por parte de la empresa. </p>
                    <div>
                        <ul>
                            <li><b>ACTA CONSITUTIVA DE LA EMPRESA </b></li>
                            <li><b>
                                    PODER DEL REPRESENTANTE LEGAL (En caso de existir un representante legal
                                    distinto al definido en el acta constitutiva, anexar el poder legal).  
                                 </b>
                            </li>
                            <li><b>IDENTIFICACIÓN OFICIAL DEL REPRESENTANTE LEGAL</b></li>
                            <li><b>CONSTANCIA DE SITUACIÓN FISCAL  </b></li>
                            <li><b>COMPROBANTE DE DOMICILIO </b></li>
                            <li><b>
                                    ACUSE DE AFILIACIÓN AL IMSS, DE LOS COLABORADORES QUE SERAN INSCRITOS AL FONDO DE PENSIÓN  
                                 </b>
                            </li>
                        </ul>
                    </div>
                    
                    <p>
                        Dicha documentación es de suma importancia, pues es necesaria para la elaboración del contrato 
                        de prestación de servicio. Anexamos un ejemplo de contrato para la revisión de este. 
                    </p>
                    <p><b>Tambien te dejamos tus datos de accesos para subir dicha informacion: </b></p>
                    $base->accessos
                    $base->pie
                    $base->estilo";

        return $html;
        
    }

    private function estilo_correo(): string
    {
        return "<style>
                    html{
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 12px;
                    }

                    li {
                        padding: 5px;
                    }
                    .pie{
                        color: #0979AE;
                    }

                </style>";

    }

    private function pie(string $dom_comercial, string $link_web_oficial, string $nombre_comercial): string
    {
        return "Quedamos a su disposicion para cualquier duda o aclaracion. <br> $dom_comercial <br> $link_web_oficial <br>$nombre_comercial";

    }


}
