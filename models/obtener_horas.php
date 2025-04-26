<?php
require_once 'conexion.php'; 

$conexion = new conexion();
$conn = $conexion->conectar();

if (!$conn) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

$cedula = $_POST['cedula'];

// Consulta para obtener las horas de entrada y salida
$query = "SELECT nombre, apellido,hora_entrada_mnn, hora_salida_mnn, hora_entrada_tarde, hora_salida_tarde FROM empleados WHERE cedula = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $cedula);

// Ejecutar la consulta
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_store_result($stmt);
    
    mysqli_stmt_bind_result($stmt, $nombre,$apellido,$hora_entrada_mnn, $hora_salida_mnn, 
    $hora_entrada_tarde, $hora_salida_tarde,);

    // Obtener resultados
    if (mysqli_stmt_fetch($stmt)) {
        // Datos obtenidos correctamente
        $result = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'hora_entrada_mnn' => $hora_entrada_mnn,
            'hora_salida_mnn' => $hora_salida_mnn,
            'hora_entrada_tarde' => $hora_entrada_tarde,
            'hora_salida_tarde' => $hora_salida_tarde
        ];
        echo json_encode($result);
    } else {
        // No se encontraron datos para la cédula especificada
        echo json_encode(['error' => 'No se encontraron datos para el empleado con esa cédula']);
    }
} else {
    // Error en la ejecución de la consulta
    echo json_encode(['error' => 'Error al obtener las horas de entrada y salida']);
}

// Cerrar la declaración y conexión
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
