<?php
require_once '../Database/conexion.php';

class MultaRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new conexion())->conectar();
    }

    public function getAsistenciaId(string $cedula, string $fecha): ?int
    {
        $sql = "SELECT id_asis 
                FROM asistencia 
                WHERE fecha = ? AND ced_emp_per = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $fecha, $cedula);
        $stmt->execute();
        $stmt->bind_result($idAsis);
        if ($stmt->fetch()) {
            $stmt->close();
            return (int)$idAsis;
        }
        $stmt->close();
        return null;
    }

    public function existsInasistencia(int $idAsis): bool
    {
        $sql = "SELECT COUNT(*) 
                FROM multas 
                WHERE id_asis_per = ? AND tipo_multa = 'Inasistencia'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idAsis);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }

    public function sumAtrasoSalida(int $idAsis): float
    {
        $sql = "SELECT SUM(multa) 
                FROM multas 
                WHERE id_asis_per = ? 
                  AND (tipo_multa = 'Atraso' OR tipo_multa = 'Salida Temprana')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idAsis);
        $stmt->execute();
        $stmt->bind_result($sum);
        $stmt->fetch();
        $stmt->close();
        return (float)$sum;
    }

    public function deleteAtrasoSalida(int $idAsis): bool
    {
        $sql = "DELETE 
                FROM multas 
                WHERE id_asis_per = ? 
                  AND (tipo_multa = 'Atraso' OR tipo_multa = 'Salida Temprana')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idAsis);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function insertMulta(int $idAsis, string $tipo, float $valor): bool
    {
        $sql = "INSERT INTO multas (id_asis_per, tipo_multa, multa) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isd", $idAsis, $tipo, $valor);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function updateEmpleadoSalary(string $cedula, float $delta): bool
    {
        // delta positivo añade, negativo resta
        $sql = "UPDATE empleados SET sueldo = sueldo + ? WHERE cedula = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ds", $delta, $cedula);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
