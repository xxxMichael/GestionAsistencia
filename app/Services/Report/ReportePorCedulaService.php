<?php

require_once('C:\xampp\htdocs\GestionAsistencia\app\libraries\fpdf186\fpdf.php');
require_once('C:\xampp\htdocs\GestionAsistencia\app\Services\Report\IReportService.php');

class ReportePorCedulaService implements IReportService {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function generate(array $params = []): void {
        if (!isset($params['cedula'])) {
            throw new \Exception('Cedula es requerida.');
        }

        $cedula = $params['cedula'];
        $con = $this->conexion->conectar();

        $sql = $con->prepare("SELECT a.*, e.*, m.* FROM asistencia a
                             JOIN empleados e ON e.cedula = a.ced_emp_per
                             JOIN multas m ON a.id_asis = m.id_asis_per
                             WHERE a.ced_emp_per = ?");
        $sql->bind_param("s", $cedula);
        $sql->execute();
        $respuesta = $sql->get_result();

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Reporte General de Asistencia', 0, 1, 'C');
        $pdf->Ln(10);

        // Sueldo inicial
        $sueldoInicial = 320.00;
        $totalMultas = 0.00;

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Sueldo inicial: $' . number_format($sueldoInicial, 2), 0, 1, 'L');
        $pdf->Ln(5);

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

        $sueldosPorEmpleado = [];

        while ($row = $respuesta->fetch_array()) {
            $cedula = $row['cedula'];
            $multa = floatval($row['multa']);
            $totalMultas += $multa;

            if (!isset($sueldosPorEmpleado[$cedula])) {
                $sueldosPorEmpleado[$cedula] = 320.00; // sueldo base
            }

            // Descontar la multa
            $sueldosPorEmpleado[$cedula] -= $multa;
            $sueldoActual = max(0, $sueldosPorEmpleado[$cedula]); // evita negativos

            $pdf->Cell(30, 10, $cedula, 1);
            $pdf->Cell(25, 10, $row['nombre'], 1);
            $pdf->Cell(25, 10, $row['apellido'], 1);
            $pdf->Cell(20, 10, number_format($sueldoActual, 2), 1);
            $pdf->Cell(20, 10, date('d-m-Y', strtotime($row['fecha'])), 1);
            $pdf->Cell(20, 10, number_format($multa, 2), 1);
            $pdf->Cell(30, 10, $row['tipo_multa'], 1);
            $pdf->Ln();
        }

        $sueldoFinal = max(0, $sueldoInicial - $totalMultas); // evita negativos
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Total de multas acumuladas: $' . number_format($totalMultas, 2), 0, 1, 'L');
        $pdf->Cell(0, 10, 'Sueldo restante: $' . number_format($sueldoFinal, 2), 0, 1, 'L');

        $pdf->Output();
    }
}
