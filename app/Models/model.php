<?php
class EnlacesPaginas
{
    public static function enlacesPaginasModel($enlacesModel)
    {  //para cargar las paginas
        if ($enlacesModel == "User/empleados" || $enlacesModel == "servicios" || $enlacesModel == "contactanos"|| $enlacesModel == "GestionInOut"||$enlacesModel=="Reportes/reporteSemanalG"||$enlacesModel=="Reportes/reporteMensualG"||$enlacesModel=="Reportes/reporteGeneralG"||$enlacesModel=="Reportes/reporteE") {
            $module = "./views/" . $enlacesModel . ".php";
        } else {
            $module = "./views/Asistencia/inicio.php";  //para cargar inicialmente a la ventana inicio
        }
        return $module;
    }   

}

?>