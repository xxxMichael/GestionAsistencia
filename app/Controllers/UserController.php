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
                include "views/plantilla.php";
            }else{
                include "views/interfaces/GestionInOut.php";
  
            }
        } else {
            include "views/login.php";
        }
    }

    public function EnlacesPaginasController()
    {
        if (isset($_GET["action"])) {     //el action llega de..
            $enlacesController = $_GET["action"];
        } else {
            $enlacesController = "inicio.php";
        }

        $respuesta = EnlacesPaginas::enlacesPaginasModel($enlacesController);
        include $respuesta;
    }
}
?>