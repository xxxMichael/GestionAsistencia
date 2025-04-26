<?php

date_default_timezone_set('America/Guayaquil');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = json_decode(file_get_contents('php://input'), true); // Obtener datos JSON enviados

    // Verificar la existencia y validez de los datos esperados
    if (isset($datos['tipoRegistro'], $datos['hora'], $datos['cedulaEmpleado'])) {
        $tipoRegistro = $datos['tipoRegistro'];
        $hora = $datos['hora'];
        $cedulaEmpleado = $datos['cedulaEmpleado'];
        $fecha = date('Y-m-d');

        // Crear instancia de conexión (usando tu lógica de conexión)
        require_once 'conexion.php';
        $conexion = new conexion();
        $conn = $conexion->conectar();

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Verificar si ya existe un registro para la fecha y cédula del empleado
        $sqlVerificar = "SELECT COUNT(*) as count FROM asistencia WHERE fecha = '$fecha' AND ced_emp_per = '$cedulaEmpleado'";
        $result = $conn->query($sqlVerificar);

        if ($result === false) {
            echo json_encode(['success' => false, 'error' => 'Error al verificar la existencia de la fila: ' . $conn->error]);
            exit;
        }

        $row = $result->fetch_assoc();
        $existenciaFila = $row['count'];

        // Preparar la consulta SQL según el tipo de registro y la existencia de la fila
        switch ($tipoRegistro) {
            case 'entradaManana':
                if ($existenciaFila == 0) {
                    // Si no existe una fila, insertar una nueva
                    $sql = "INSERT INTO asistencia (fecha, hora_entrada_mnn, ced_emp_per) VALUES ('$fecha', '$hora', '$cedulaEmpleado')";
                } else {
                    // Si ya existe una fila, actualizar la existente
                    $sql = "UPDATE asistencia SET hora_entrada_mnn = '$hora' WHERE fecha = '$fecha' AND ced_emp_per = '$cedulaEmpleado'";
                }
                break;
            case 'salidaManana':
                if ($existenciaFila == 0) {
                    // Si no existe una fila, insertar una nueva
                    $sql = "INSERT INTO asistencia (fecha, hora_salida_mnn, ced_emp_per) VALUES ('$fecha', '$hora', '$cedulaEmpleado')";
                } else {
                    // Si ya existe una fila, actualizar la existente
                    $sql = "UPDATE asistencia SET hora_salida_mnn = '$hora' WHERE fecha = '$fecha' AND ced_emp_per = '$cedulaEmpleado'";
                }
                break;
            case 'entradaTarde':
                if ($existenciaFila == 0) {
                    // Si no existe una fila, insertar una nueva
                    $sql = "INSERT INTO asistencia (fecha, hora_entrada_tarde, ced_emp_per) VALUES ('$fecha', '$hora', '$cedulaEmpleado')";
                } else {
                    // Si ya existe una fila, actualizar la existente
                    $sql = "UPDATE asistencia SET hora_entrada_tarde = '$hora' WHERE fecha = '$fecha' AND ced_emp_per = '$cedulaEmpleado'";
                }
                break;
            case 'salidaTarde':
                if ($existenciaFila == 0) {
                    // Si no existe una fila, insertar una nueva
                    $sql = "INSERT INTO asistencia (fecha, hora_salida_tarde, ced_emp_per) VALUES ('$fecha', '$hora', '$cedulaEmpleado')";
                } else {
                    $sql = "UPDATE asistencia SET hora_salida_tarde = '$hora' WHERE fecha = '$fecha' AND ced_emp_per = '$cedulaEmpleado'";
                }
                break;
            default:
                echo "Tipo de registro no válido";
                exit; // Salir del script si el tipo de registro no es válido
        }

        // Ejecutar la consulta SQL
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'fec' => $fecha]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        // Mostrar los datos que faltan
        $faltanDatos = [];
        if (!isset($datos['tipoRegistro'])) {
            $faltanDatos[] = 'tipoRegistro';
        }
        if (!isset($datos['hora'])) {
            $faltanDatos[] = 'hora';
        }
        if (!isset($datos['cedulaEmpleado'])) {
            $faltanDatos[] = 'cedulaEmpleado';
        }
        echo json_encode(['success' => false, 'error' => 'Datos insuficientes para procesar la solicitud. Faltan los siguientes datos: ' . implode(', ', $faltanDatos)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>