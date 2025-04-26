<body>
<!-- Interfaz de Reporte Semanal -->
<section class="content-section">
    <h2>Generar Reporte Semanal</h2>
    <form action="views/interfaces/reporteSG.php" method="post" target="reporteFrame" onsubmit="return validarFecha();">
        <div class="form-group">
            <label for="cedula">Cédula:</label>
            <input type="text" id="cedula" name="cedula" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Generar Reporte</button>
    </form>
</section>
<script>
    function validarFecha() {
        const fechaSeleccionada = new Date(document.getElementById("fecha").value);
        const fechaActual = new Date();
        fechaActual.setHours(0, 0, 0, 0);

        if (fechaSeleccionada >= fechaActual) {
            alert("No puedes seleccionar la fecha de hoy ni fechas posteriores.");
            return false;
        }
        return true;
    }
</script>

</body>