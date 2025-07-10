<?php
// Servicio para generar reportes mensuales de asistencia de empleados.
require_once('C:\xampp\htdocs\GestionAsistencia\app\libraries\fpdf186\fpdf.php');
require_once('C:\xampp\htdocs\GestionAsistencia\app\Services\Report\IReportService.php');

class ReporteMensualService implements IReportService {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function generate(array $params = []): void {
        if (!isset($params['cedula'], $params['mes'], $params['anio'])) {
            throw new \Exception('Cedula, mes y año son requeridos.');
        }

        $cedula = $params['cedula'];
        $mes = $params['mes'];
        $anio = $params['anio'];

        $fechaSeleccionada = new DateTime("$anio-$mes-01");
        $fechaActual = new DateTime();
        $fechaActual->modify('first day of this month');

        if ($fechaSeleccionada > $fechaActual) {
            throw new \Exception('No puedes seleccionar un mes o año futuro.');
        }

        $fechaInicio = "$anio-$mes-01";
        $fechaFin = (new DateTime("$anio-$mes-01"))->modify('last day of this month')->format('Y-m-d');

        $con = $this->conexion->conectar();

        $sql = $con->prepare("SELECT a.*, e.*, m.* FROM asistencia a
                             JOIN empleados e ON e.cedula = a.ced_emp_per
                             JOIN multas m ON a.id_asis = m.id_asis_per
                             WHERE a.ced_emp_per = ? AND a.fecha BETWEEN ? AND ?");
        $sql->bind_param("sss", $cedula, $fechaInicio, $fechaFin);
        $sql->execute();
        $respuesta = $sql->get_result();

        if (!$respuesta) {
            throw new \Exception('Error en la consulta: ' . $con->error);
        }

        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Reporte de Asistencia del Mes de '.$fechaSeleccionada->format('F Y'), 0, 1, 'C');
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
