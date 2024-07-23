<?php
namespace gamboamartin\notificaciones\tests\orm;


use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\facturacion\tests\base_test;
use gamboamartin\facturacion\tests\base_test2;
use gamboamartin\notificaciones\controllers\_plantilla;
use gamboamartin\notificaciones\controllers\controlador_not_adjunto;
use gamboamartin\notificaciones\models\not_receptor;
use gamboamartin\organigrama\models\org_empresa;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\test\liberator;
use gamboamartin\test\test;
use gamboamartin\facturacion\models\fc_factura;


use stdClass;


class _plantillaTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/notificaciones/config/generales.php';
        $this->paths_conf->database = '/var/www/html/notificaciones/config/database.php';
        $this->paths_conf->views = '/var/www/html/notificaciones/config/views.php';
    }

    public function test_accesos_html(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'not_adjunto';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';


        $obj = new _plantilla();
        $obj = new liberator($obj);
        $link_acceso = 'a';
        $password = 'b';
        $usuario = 'c';
        $resultado = $obj->accesos_html($link_acceso, $password, $usuario);
        $this->assertIsString($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals("<p><b>a</b></p><p>Usuario: c</p><p>Password: b</p>",$resultado);

        errores::$error = false;
    }






}

