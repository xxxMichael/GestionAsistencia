<?php
// Iniciar la sesión si aún no se ha iniciado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    $_SESSION = array();
    session_destroy();

    header("Location: ./index.php");
    exit();
}
if (isset($_SESSION['email']) && isset($_SESSION['rol'])) {
    $email = $_SESSION['email'];
    $rol = $_SESSION['rol'];


} else {
    header("Location: ./index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
  window.CEDULA_EMPLEADO = "<?php echo addslashes($email); ?>";
</script>
    <script src="./Public/js/asistencia.js"></script>

    <title>Registro Jornada</title>
    <style>
        body {
            background-color: #2e302f;
        }

        .custom-navbar {
            background-color: #007bff;
        }

        .custom-navbar .navbar-brand,
        .custom-navbar .nav-link {
            color: #fff !important;
        }

        .custom-navbar .nav-link:hover {
            color: #ffc107 !important;
        }

        .custom-header {
            /* background: url('imges/uta-banner.jpg') no-repeat center center; */
            background-size: cover;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-header h1 {
            color: #fff;
            text-align: center;
            padding: 70px 0;
        }

        .table-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .custom-footer {
            background-color: #007bff;
            color: #fff;
        }

        .table-container h2 {
            color: #000;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        /* Estilo específico para Hora Entrada */
        td#hora_entrada_mnn,
        td#hora_entrada_tarde {
            background-color: #c6e48b;
            /* Verde pastel */
        }

        /* Estilo específico para Hora Salida */
        td#hora_salida_mnn,
        td#hora_salida_tarde {
            background-color: #fca5a5;
            /* Rojo pastel */
        }
    </style>

</head>

<body>
    <header class="custom-header">
        <h1>Empresa Electrica Ambato</h1>
    </header>
    x<nav class="navbar navbar-expand-lg navbar-light custom-navbar">
        <div class="container-fluid">
        
            <div class="navbar-text ">
                <h2 class="navbar-brand" id="nombreUser">Nombre del Empleado</h2>
                <h2 class="navbar-brand"><?php echo htmlspecialchars($email); ?></h2>
            </div>

            <?php if ($rol === 'empleado') { ?>
                <div class="text-end">
                    <form method="POST" action="">
                        <button type="submit" class="btn btn-primary" name="logout">Cerrar sesión</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="table-container">

            <table class="table table-bordered table-striped" id="dg">
                <thead class="thead-dark">

                    <tr>
                        <th scope="col" colspan="5" id="fecha"></th>
                    </tr>
                    <tr>
                        <th scope="col">Jornada</th>
                        <th scope="col">Hora Ingreso</th>
                        <th scope="col">Hora Salida</th>
                        <th scope="col">Registro Ingreso</th>
                        <th scope="col">Registro Salida</th>
                    </tr>
                    <tr id="matutina">
                        <td>Matutina</td>
                        <td id="hora_entrada_mnn">Hora Entrada</td>
                        <td id="hora_salida_mnn">Hora Salida</td>
                        <td id="registro_entrada_mnn"></td>
                        <td id="registro_salida_mnn"></td>
                    </tr>
                    <tr id="vespertina">
                        <td>Vespertina</td>
                        <td id="hora_entrada_tarde">Hora Entrada</td>
                        <td id="hora_salida_tarde">Hora Salida</td>
                        <td id="registro_entrada_tarde"></td>
                        <td id="registro_salida_tarde"></td>
                    </tr>
                </thead>

            </table>
            <div class="mb-3 d-flex justify-content-center">
                <button class="btn btn-primary" id="btn_submit">Cargando...</button>

            </div>

        </div>
    </div>


</body>

</html>