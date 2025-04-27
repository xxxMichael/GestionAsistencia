<?php
include 'conexion.php';

class Login
{
    private $conn;

    public function __construct()
    {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    public function verificarUsuario($email, $password)
    {
        $email = mysqli_real_escape_string($this->conn, $email);
        $password = mysqli_real_escape_string($this->conn, $password);

        $query = "SELECT * FROM usuarios WHERE usuario = '$email' AND contrasenia = '$password'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            return $user;
        } else {
            return false;
        }
    }
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login = new Login();
    $user = $login->verificarUsuario($email, $password);

    if ($user) {
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $user['rol'];
        if ($user['rol'] == "administrador") {
            include "./views/Layouts/plantilla.php";
        } else {
            include "./views/Asistencia/GestionInOut.php";

        }
        echo json_encode(['success' => true, 'role' => $user['rol']]);

    } else {
        echo json_encode(['success' => false]);
    }


    /*  
     */
}
?>