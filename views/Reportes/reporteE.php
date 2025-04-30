<body onload="document.getElementById('autoReporteForm').submit();">
    <!-- Interfaz de Reporte General Automática -->
    <section class="content-section">
        <h2>Generar Reporte General</h2>
        <form id="autoReporteForm" action="app/controllers/reportDispatcher.php" method="post" target="reporteFrame">
            <input type="hidden" name="tipo" value="all">
        </form>
    </section>
</body>
