<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    $_SESSION = array();
    session_destroy();

    header("Location: ./index.php");

    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EEESA</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./Public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <header class="custom-header">
        <h1>Empresa Electrica Ambato</h1>
    </header>
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
        <a class="navbar-brand" href="#">EEASA</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?action=inicio">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?action=User/empleados">Empleados</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?action=Reportes/reporteSemanalG">Reporte Semanal</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="index.php?action=Reportes/reporteMensualG">Reporte Mensual</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="index.php?action=Reportes/reporteGeneralG">Reporte General</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#" onclick="enviarReporteAll()" target="_blank">Reporte Empleados</a>
                </li>

                <div>
                    <form method="POST" action="">
                        <button type="submit" name="logout">Cerrar sesión</button>
                    </form>
                </div>
            </ul>
        </div>
    </nav>

    <main class="container mt-5">
        <section class="content-section">
            <?php
            require_once "./app/Controllers/Controller.php";
            require_once "./app/Models/model.php";
            $mvc = new MVCcontroller();
            $mvc->EnlacesPaginasController();
            ?>
        </section>
    </main>

    <form id="formReporteAll" action="app/controllers/reportDispatcher.php" method="post" target="_blank" style="display:none;">
    <input type="hidden" name="tipo" value="all">
</form>

<script>
function enviarReporteAll() {
    window.close(); // Cerrar la ventana actual
    document.getElementById('formReporteAll').submit();
}
</script>
    <footer class="custom-footer bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Sobre Nosotros</h5>
                    <p>Empresa Electrica Ambato brinda servicios eléctricos de calidad a la comunidad.</p>
                </div>
                <div class="col-md-4">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php?action=inicio" class="text-white">Inicio</a></li>
                        <li><a href="index.php?action=User/empleados" class="text-white">Empleados</a></li>
                        <li><a href="index.php?action=reporteSemanalG" class="text-white">Reporte Semanal</a></li>
                        <li><a href="index.php?action=reporteMensualG" class="text-white">Reporte Mensual</a></li>
                        <li><a href="index.php?action=reporteGeneralG" class="text-white">Reporte General</a></li>
                        <li><a href="index.php?action=reporteE" class="text-white">Reporte Empleados</a></li>

                        <!--<li><a href="index.php?action=GestionInOut" class="text-white">Registro Asistencia</a></li>-->
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Síguenos</h5>
                    <a href="#" class="text-white mr-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white mr-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white mr-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white mr-2"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <p class="mb-0">Proyecto Grupo 5 @Cuarto Software &copy; 2024</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>