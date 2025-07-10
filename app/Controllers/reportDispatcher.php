<?php
require_once './ReportController.php'; // ajusta la ruta según tu estructura

$controller = new ReportController();

// Detecta el tipo de reporte a generar
$tipo = $_POST['tipo'] ;

switch ($tipo ) {
    case 'general':
        $cedula = $_POST['cedula'] ?? '';
        $controller->reportePorCedula($cedula);
        break;

    case 'semanal':
        $cedula = $_POST['cedula'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $controller->reporteSemanal($cedula, $fecha);
        break;

    case 'mensual':
        $cedula = $_POST['cedula'] ?? '';
        $mes = $_POST['mes'] ?? '';
        $anio = $_POST['anio'] ?? '';
        $controller->reporteMensual($cedula, $mes, $anio);
        break;
    case 'all':
        $controller->reporteGeneral();

        
        break;
    default: // general
      //  $controller->reporteGeneral();
        break;
}
