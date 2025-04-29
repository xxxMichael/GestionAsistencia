<?php
require_once '../Database/conexion.php';

class AsistenciaRepository
{
    private $conn;

    public function __construct()
    {
        $conexion = new conexion();
        $this->conn = $conexion->conectar();
    }

    public function obtenerAsistenciaPorFechaYCedula($cedula, $fecha)
    {
        $query = "SELECT hora_entrada_mnn, hora_salida_mnn, hora_entrada_tarde, hora_salida_tarde 
                  FROM asistencia 
                  WHERE ced_emp_per = ? AND fecha = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $cedula, $fecha);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $hEntMan, $hSalMan, $hEntTar, $hSalTar);

        if (mysqli_stmt_fetch($stmt)) {
            return [
                'hora_entrada_mnn' => $hEntMan,
                'hora_salida_mnn' => $hSalMan,
                'hora_entrada_tarde' => $hEntTar,
                'hora_salida_tarde' => $hSalTar
            ];
        }

        return null;
    }

    public function existeAsistencia($cedula, $fecha)
    {
        $sql = "SELECT COUNT(*) as count FROM asistencia WHERE fecha = ? AND ced_emp_per = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $fecha, $cedula);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function insertarAsistencia($cedula, $fecha, $campo, $hora)
    {
        $sql = "INSERT INTO asistencia (fecha, ced_emp_per, $campo) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception("Error al preparar INSERT: " . $this->conn->error);
        }
        // bind_param: 'sss' porque las tres columnas son strings
        $stmt->bind_param('sss', $fecha, $cedula, $hora);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
    
    public function actualizarAsistencia($cedula, $fecha, $campo, $hora)
    {
        $sql = "UPDATE asistencia SET $campo = ? WHERE fecha = ? AND ced_emp_per = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception("Error al preparar UPDATE: " . $this->conn->error);
        }
        $stmt->bind_param('sss', $hora, $fecha, $cedula);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
    
    public function obtenerHorasEmpleado($cedula)
    {
        $query = "SELECT nombre, apellido,hora_entrada_mnn, hora_salida_mnn, hora_entrada_tarde, hora_salida_tarde FROM empleados WHERE cedula = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $cedula);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            mysqli_stmt_bind_result(
                $stmt,
                $nombre,
                $apellido,
                $hora_entrada_mnn,
                $hora_salida_mnn,
                $hora_entrada_tarde,
                $hora_salida_tarde,
            );
            if (mysqli_stmt_fetch($stmt)) {

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
                echo json_encode(['error' => 'No se encontraron datos para el empleado con esa cédula']);
            }
        } else {
            echo json_encode(['error' => 'Error al obtener las horas de entrada y salida']);
        }
    }

}
