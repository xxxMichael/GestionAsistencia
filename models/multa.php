<?php
date_default_timezone_set('America/Guayaquil');

require_once 'conexion.php';

$conexion = new conexion();
$conn = $conexion->conectar();

if ($conn === false) {
    die(json_encode(['error' => 'Error al conectar con la base de datos']));
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$valor = $data['valor'] ?? null;
$tipomulta = $data['tipomulta'] ?? null;
$cedulaEmpleado = $data['cedulaEmpleado'] ?? null;

if (!empty($valor) && !empty($tipomulta) && !empty($cedulaEmpleado)) {
    $fechaActual = date('Y-m-d');

    $sqlAsistencia = "SELECT id_asis FROM asistencia WHERE fecha = '$fechaActual' AND ced_emp_per = '$cedulaEmpleado'";
    $resultAsistencia = $conn->query($sqlAsistencia);

    if ($resultAsistencia === false) {
        die(json_encode(['error' => 'Error al ejecutar la consulta de asistencia: ' . $conn->error]));
    }

    if ($resultAsistencia->num_rows > 0) {
        $rowAsistencia = $resultAsistencia->fetch_assoc();
        $idAsis = $rowAsistencia['id_asis'];

        if ($tipomulta === "Inasistencia") {
            // Verificar si ya existe una multa por inasistencia para el mismo ID de asistencia
            $sqlMultaInasistencia = "SELECT * FROM multas WHERE id_asis_per = '$idAsis' AND tipo_multa = 'Inasistencia'";
            $resultMultaInasistencia = $conn->query($sqlMultaInasistencia);

            if ($resultMultaInasistencia === false) {
                die(json_encode(['error' => 'Error al verificar la existencia de multa por inasistencia: ' . $conn->error]));
            }

            if ($resultMultaInasistencia->num_rows > 0) {
                echo json_encode(['message' => 'Ya existe una multa por inasistencia para este día. No se ha registrado una nueva multa ni se ha realizado un descuento.']);
            } else {
                // Consultar el valor de las multas de atraso y salida temprana existentes
                $sqlMultasPre = "SELECT multa FROM multas WHERE id_asis_per = '$idAsis' AND (tipo_multa = 'Atraso' OR tipo_multa = 'Salida Temprana')";
                $resultMultasPre = $conn->query($sqlMultasPre);

                if ($resultMultasPre === false) {
                    die(json_encode(['error' => 'Error al consultar las multas anteriores de atraso o salida temprana: ' . $conn->error]));
                }

                $valorTotalMultas = 0;
                while ($rowMulta = $resultMultasPre->fetch_assoc()) {
                    $valorTotalMultas += $rowMulta['multa'];
                }

                // Eliminar las multas de atraso y salida temprana existentes
                $sqlEliminarMulta = "DELETE FROM multas WHERE id_asis_per = '$idAsis' AND (tipo_multa = 'Atraso' OR tipo_multa = 'Salida Temprana')";
                if ($conn->query($sqlEliminarMulta) === false) {
                    die(json_encode(['error' => 'Error al eliminar multas anteriores de atraso o salida temprana: ' . $conn->error]));
                }

                // Reponer el valor de las multas eliminadas al sueldo del empleado
                $sqlActualizarSueldo = "UPDATE empleados SET sueldo = sueldo + '$valorTotalMultas' WHERE cedula = '$cedulaEmpleado'";
                if ($conn->query($sqlActualizarSueldo) === false) {
                    die(json_encode(['error' => 'Error al actualizar el sueldo del empleado: ' . $conn->error]));
                }

                // Registrar la nueva multa de inasistencia
                $queryMulta = "INSERT INTO multas (id_asis_per, tipo_multa, multa) VALUES ('$idAsis', 'Inasistencia', '$valor')";
                if ($conn->query($queryMulta) === true) {
                    // Restar el valor de la multa del sueldo del empleado
                    $sqlRestarMulta = "UPDATE empleados SET sueldo = sueldo - '$valor' WHERE cedula = '$cedulaEmpleado'";
                    if ($conn->query($sqlRestarMulta) === false) {
                        die(json_encode(['error' => 'Error al actualizar el sueldo del empleado tras registrar la multa: ' . $conn->error]));
                    }
                    echo json_encode(['message' => 'Multa de inasistencia registrada correctamente y sueldo actualizado']);
                } else {
                    echo json_encode(['error' => 'Error al registrar la multa de inasistencia: ' . $conn->error]);
                }
            }
        } else {
            // Registrar la nueva multa
            $queryMulta = "INSERT INTO multas (id_asis_per, tipo_multa, multa) VALUES ('$idAsis', '$tipomulta', '$valor')";
            if ($conn->query($queryMulta) === true) {
                // Restar el valor de la multa del sueldo del empleado
                $sqlRestarMulta = "UPDATE empleados SET sueldo = sueldo - '$valor' WHERE cedula = '$cedulaEmpleado'";
                if ($conn->query($sqlRestarMulta) === false) {
                    die(json_encode(['error' => 'Error al actualizar el sueldo del empleado tras registrar la multa: ' . $conn->error]));
                }
                echo json_encode(['message' => 'Multa registrada correctamente y sueldo actualizado']);
            } else {
                echo json_encode(['error' => 'Error al registrar la multa: ' . $conn->error]);
            }
        }
    } else {
        echo json_encode([
            'error' => 'No se encontró el registro de asistencia para hoy',
            'valor' => $valor,
            'tipomulta' => $tipomulta,
            'cedulaEmpleado' => $cedulaEmpleado,
            'fecha' => $fechaActual
        ]);
    }
} else {
    echo json_encode(['error' => 'Faltan parámetros']);
}
?>
