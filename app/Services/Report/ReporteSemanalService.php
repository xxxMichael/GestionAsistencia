<?php

require_once('C:\xampp\htdocs\GestionAsistencia\app\libraries\fpdf186\fpdf.php');
require_once('C:\xampp\htdocs\GestionAsistencia\app\Services\Report\IReportService.php');

class ReporteSemanalService implements IReportService
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function generate(array $params = []): void
    {
        if (!isset($params['cedula']) || !isset($params['fecha'])) {
            throw new \Exception('Cedula y fecha son requeridas.');
        }

        $cedula = $params['cedula'];
        $fechaSeleccionada = new DateTime($params['fecha']);

        // Ajustar inicio de semana a lunes y fin a viernes
        $inicioSemana = clone $fechaSeleccionada;
        $finSemana = clone $fechaSeleccionada;
        $inicioSemana->modify('monday this week');
        $finSemana->modify('friday this week');

        $fechaInicio = $inicioSemana->format('Y-m-d');
        $fechaFin = $finSemana->format('Y-m-d');

        $con = $this->conexion->conectar();

        $sql = $con->prepare("SELECT a.*, e.*, m.* FROM asistencia a
                              JOIN empleados e ON e.cedula = a.ced_emp_per
                              JOIN multas m ON a.id_asis = m.id_asis_per
                              WHERE a.ced_emp_per = ? AND a.fecha BETWEEN ? AND ?");
        $sql->bind_param("sss", $cedula, $fechaInicio, $fechaFin);
        $sql->execute();
        $respuesta = $sql->get_result();

        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Reporte Semanal de Asistencia del ' . $fechaInicio . ' al ' . $fechaFin, 0, 1, 'C');
        //$pdf->Image('../../../Public/images/eeasa.png', 10, 10, 20);
        $pdf->Ln(20);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 10, 'Cedula', 1);
        $pdf->Cell(25, 10, 'Nombre', 1);
        $pdf->Cell(25, 10, 'Apellido', 1);
        $pdf->Cell(20, 10, 'Sueldo', 1);
        $pdf->Cell(20, 10, 'Fecha', 1);
        $pdf->Cell(20, 10, 'Multa', 1);
        $pdf->Cell(30, 10, 'Tipo multa', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        while ($row = $respuesta->fetch_array()) {
            $pdf->Cell(30, 10, $row['cedula'], 1);
            $pdf->Cell(25, 10, $row['nombre'], 1);
            $pdf->Cell(25, 10, $row['apellido'], 1);
            $pdf->Cell(20, 10, $row['sueldo'], 1);
            $pdf->Cell(20, 10, date('d-m-Y', strtotime($row['fecha'])), 1);
            $pdf->Cell(20, 10, $row['multa'], 1);
            $pdf->Cell(30, 10, $row['tipo_multa'], 1);
            $pdf->Ln();
        }

        $pdf->Output();
    }
}
