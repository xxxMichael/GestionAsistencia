<?php
class EnlacesPaginas
{
    public static function enlacesPaginasModel($enlacesModel)
    {  //para cargar las paginas
        if ($enlacesModel == "empleados" || $enlacesModel == "servicios" || $enlacesModel == "contactanos"|| $enlacesModel == "GestionInOut"||$enlacesModel=="reporteSemanalG"||$enlacesModel=="reporteMensualG"||$enlacesModel=="reporteGeneralG"||$enlacesModel=="reporteE") {
            $module = "views/interfaces/" . $enlacesModel . ".php";
        } else {
            $module = "views/interfaces/inicio.php";  //para cargar inicialmente a la ventana inicio
        }
        return $module;
    }   

}

?>