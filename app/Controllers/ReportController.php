<?php

require_once '../Services/Report/ReporteGeneralEmpleadoService.php';

//require_once '../app/Services/Report/ReporteGeneralEmpleadoService.php';
require_once '../Services/Report/ReportePorCedulaService.php';
require_once '../Services/Report/ReporteMensualService.php';
require_once '../Services/Report/ReporteSemanalService.php';
require_once '../Database/conexion.php';

class ReportController
{
    public function reporteGeneral()
    {
        $conexion = new conexion();
        $reporteService = new ReporteGeneralEmpleadoService($conexion);
        $reporteService->generate();
    }

    public function reportePorCedula($cedula)
    {
        $conexion = new conexion();
        $reporteService = new ReportePorCedulaService($conexion);
        $reporteService->generate(['cedula' => $cedula]);
    }

    public function reporteSemanal($cedula, $fecha)
    {
        $conexion = new conexion();
        $reporteService = new ReporteSemanalService($conexion);
        $reporteService->generate([
            'cedula' => $cedula,
            'fecha' => $fecha
        ]);
    }

    public function reporteMensual($cedula, $mes, $anio)
    {
        $conexion = new conexion();
        $reporteService = new ReporteMensualService($conexion);
        $reporteService->generate([
            'cedula' => $cedula,
            'mes' => $mes,
            'anio' => $anio
        ]);
    }
}
