<?php     ///este envia el modelo al MODEL
class MVCcontroller
{

    public function plantilla()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (
            isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true
        ) {
            if ($_SESSION['rol'] == "administrador") {
                include "./views/Layouts/plantilla.php";
            }else{
                include "./views/Asistencia/GestionInOut.php";
            }
        } else {
            include "./views/Auth/login.php";
        }
    }

    public function EnlacesPaginasController()
    {
        if (isset($_GET["action"])) {     //el action llega de..
            $enlacesController = $_GET["action"];
        } else {
            $enlacesController = "./views/Asistencia/inicio.php"; //si no hay action, se carga la vista por defecto
        }

        $respuesta = EnlacesPaginas::enlacesPaginasModel($enlacesController);
        include $respuesta;
    }
}
?>