<section class="content-section">
        <h2>Generar Reporte General</h2>
        <form action="views/interfaces/reporteMG.php" method="post" target="reporteFrame" onsubmit="return validarFecha();">
            <div class="form-group">
                <label for="cedula">Cédula:</label>
                <input type="text" id="cedula" name="cedula" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="mes">Mes:</label>
                <select id="mes" name="mes" class="form-control" required>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>
            <div class="form-group">
                <label for="anio">Año:</label>
                <input type="number" id="anio" name="anio" class="form-control" min="1900" max="2100" required>
            </div>
            <button type="submit" class="btn btn-primary">Generar Reporte</button>
        </form>
    </section>

    <script>
        function validarFecha() {
            const mesSeleccionado = document.getElementById("mes").value;
            const anioSeleccionado = document.getElementById("anio").value;
            const fechaSeleccionada = new Date(anioSeleccionado, mesSeleccionado - 1, 1); // Mes en JavaScript es 0-11
            const fechaActual = new Date();
            
            // Ajustar fecha actual al primer día del mes actual para comparación
            fechaActual.setDate(1);
            fechaActual.setHours(0, 0, 0, 0);

            if (fechaSeleccionada > fechaActual) {
                alert("No puedes seleccionar un mes o año futuro.");
                return false;
            }
            return true;
        }
    </script>