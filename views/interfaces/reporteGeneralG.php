        <body>
        <!-- Interfaz de Reporte Semanal -->
        <section class="content-section">
            <h2>Generar Reporte General</h2>
            <form action="views/interfaces/reporteGG.php" method="post" target="reporteFrame" onsubmit="return validarFecha();">
                <div class="form-group">
                    <label for="cedula">Cédula:</label>
                    <input type="text" id="cedula" name="cedula" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </form>
        </section>

        </body>