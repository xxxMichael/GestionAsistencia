<?php
require('../../fpdf186/fpdf.php');
include("../../models/conexion.php");

$conn = new conexion();
$con = $conn->conectar();

if ($con->connect_error) {
    die('Error de conexión (' . $con->connect_errno . ') ' . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $fechaSeleccionada = new DateTime($_POST['fecha']);

    // Ajustar el inicio de la semana a lunes y el fin de la semana a viernes
    $inicioSemana = clone $fechaSeleccionada;
    $finSemana = clone $fechaSeleccionada;
    $inicioSemana->modify('monday this week');
    $finSemana->modify('friday this week');

    // Formatear las fechas para la consulta SQL
    $fechaInicio = $inicioSemana->format('Y-m-d');
    $fechaFin = $finSemana->format('Y-m-d');

    // Consulta SQL con seguridad para filtrar por la semana seleccionada y la cédula
    $sqlSelect = $con->prepare("SELECT a.*, e.*, m.* FROM asistencia a
                               JOIN empleados e ON e.cedula = a.ced_emp_per
                               JOIN multas m ON a.id_asis = m.id_asis_per
                               WHERE a.ced_emp_per = ? AND a.fecha BETWEEN ? AND ?");
    $sqlSelect->bind_param("sss", $cedula, $fechaInicio, $fechaFin);
    $sqlSelect->execute();
    $respuesta = $sqlSelect->get_result();

    // Verificar si la consulta fue exitosa
    if (!$respuesta) {
        die('Error en la consulta: ' . $con->error);
    }

    // Inicio de la generación del PDF
    $fpdf = new FPDF();
    $fpdf->AddPage();

    // Título del reporte
    $fpdf->SetFont('Arial', 'B', 14);
    $fpdf->Cell(0, 10, 'Reporte Semanal de Asistencia del ' . $fechaInicio . ' al ' . $fechaFin, 0, 1, 'C');

    // Imagen (ajusta la ruta según la ubicación real)
    $fpdf->Image('../../images/eeasa.png', 10, 10, 20);

    // Salto de línea para el encabezado de la tabla
    $fpdf->Ln(20);

    // Encabezados de las columnas
    $fpdf->SetFont('Arial', 'B', 10);
    $fpdf->Cell(30, 10, 'Cedula', 1);
    $fpdf->Cell(25, 10, 'Nombre', 1);
    $fpdf->Cell(25, 10, 'Apellido', 1);
    $fpdf->Cell(20, 10, 'Sueldo', 1);
    $fpdf->Cell(20, 10, 'Fecha', 1);
    $fpdf->Cell(20, 10, 'Multa', 1);
    $fpdf->Cell(30, 10, 'Tipo multa', 1);
    $fpdf->Ln();

    // Datos de los empleados
    $fpdf->SetFont('Arial', '', 10);
    while ($row = $respuesta->fetch_array()) {
        $cedula = $row['cedula'];
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];
        $sueldo = $row['sueldo'];
        $fecha = date('d-m-Y', strtotime($row['fecha'])); // Formato de fecha específico
        $multa = $row['multa'];
        $tipMulta = $row['tipo_multa'];

        $fpdf->Cell(30, 10, $cedula, 1);
        $fpdf->Cell(25, 10, $nombre, 1);
        $fpdf->Cell(25, 10, $apellido, 1);
        $fpdf->Cell(20, 10, $sueldo, 1);
        $fpdf->Cell(20, 10, $fecha, 1);
        $fpdf->Cell(20, 10, $multa, 1);
        $fpdf->Cell(30, 10, $tipMulta, 1);
        $fpdf->Ln();
    }

    // Salida del PDF
    $fpdf->Output(); // Método para mostrar el reporte
}
?>
