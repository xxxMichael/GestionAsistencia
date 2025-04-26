<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Empleados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h2>Empleados</h2>

        <table class="table table-bordered table-striped" id="dg">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Cédula</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Jornada Matutina</th>
                    <th scope="col">Jornada Vespertina</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="mb-3">
            <button class="btn btn-primary" onclick="newUser()">Agregar <i class="fas fa-plus"></i></button>
        </div>
    </div>

    <div class="modal fade" id="dlg" tabindex="-1" aria-labelledby="dlgLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="fm" method="post" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title" id="dlgLabel">Empleados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="validationErrors" class="alert alert-danger d-none"></div>
                        <div class="form-group">
                            <label for="emp_cedula">Cédula:</label>
                            <input type="text" name="emp_cedula" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="emp_nombre">Nombre:</label>
                            <input type="text" name="emp_nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="emp_apellido">Apellido:</label>
                            <input type="text" name="emp_apellido" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="emp_telefono">Teléfono:</label>
                            <input type="text" name="emp_telefono" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="emp_direccion">Dirección:</label>
                            <input type="text" name="emp_direccion" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="emp_hora_entrada_mnn">Hora de entrada Matutina:</label>
                            <input type="time" name="emp_hora_entrada_mnn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="emp_hora_salida_mnn">Hora de salida Matutina:</label>
                            <input type="time" name="emp_hora_salida_mnn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="emp_hora_entrada_tarde">Hora de entrada Vespertina:</label>
                            <input type="time" name="emp_hora_entrada_tarde" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="emp_hora_salida_tarde">Hora de salida Vespertina:</label>
                            <input type="time" name="emp_hora_salida_tarde" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var url;

        function newUser() {
            $('#dlg').modal('show');
            $('#fm')[0].reset();
            $('#fm').find('input[name="emp_cedula"]').prop('readonly', false);
            $('#validationErrors').addClass('d-none').text('');
            url = 'models/guardar.php';
        }
        function editUser() {
            var row = $('#dg tbody').find('tr.selected');
            if (row.length > 0) {
                var cedula = row.find('td:eq(0)').text();
                var nombre = row.find('td:eq(1)').text();
                var apellido = row.find('td:eq(2)').text();
                var telefono = row.find('td:eq(3)').text();
                var direccion = row.find('td:eq(4)').text();
                var jornadaMatutina = row.find('td:eq(5)').text().split('-');
                var jornadaVespertina = row.find('td:eq(6)').text().split('-');

                var hora_entrada_mnn = jornadaMatutina[0];
                var hora_salida_mnn = jornadaMatutina[1];
                var hora_entrada_tarde = jornadaVespertina[0];
                var hora_salida_tarde = jornadaVespertina[1];

                $('#fm').find('input[name="emp_cedula"]').val(cedula).prop('readonly', true); 
                $('#fm').find('input[name="emp_nombre"]').val(nombre);
                $('#fm').find('input[name="emp_apellido"]').val(apellido);
                $('#fm').find('input[name="emp_telefono"]').val(telefono);
                $('#fm').find('input[name="emp_direccion"]').val(direccion);
                $('#fm').find('input[name="emp_hora_entrada_mnn"]').val(hora_entrada_mnn);
                $('#fm').find('input[name="emp_hora_salida_mnn"]').val(hora_salida_mnn);
                $('#fm').find('input[name="emp_hora_entrada_tarde"]').val(hora_entrada_tarde);
                $('#fm').find('input[name="emp_hora_salida_tarde"]').val(hora_salida_tarde);

                $('#validationErrors').addClass('d-none').text('');
                url = 'models/editar.php?cedula=' + cedula;
                $('#dlg').modal('show');
            }
        }

        function validateForm() {
            var cedula = $('input[name="emp_cedula"]').val().trim();
            var nombre = $('input[name="emp_nombre"]').val().trim();
            var apellido = $('input[name="emp_apellido"]').val().trim();
            var telefono = $('input[name="emp_telefono"]').val().trim();
            var direccion = $('input[name="emp_direccion"]').val().trim();
            var errors = [];

            // Validar campos vacíos
            if (!cedula || !nombre || !apellido || !telefono || !direccion) {
                errors.push('Todos los campos son obligatorios.');
            }

            // Validar cédula (ejemplo: longitud de 10 dígitos)
            if (cedula && !/^\d{10}$/.test(cedula)) {
                errors.push('La cédula debe tener 10 dígitos.');
            }

            // Validar teléfono (debe empezar con 09 y tener 10 dígitos)
            if (telefono && !/^09\d{8}$/.test(telefono)) {
                errors.push('El número de teléfono debe tener 10 dígitos y empezar con 09.');
            }

            // Validar nombre y apellido (solo letras)
            if ((nombre && !/^[a-zA-Z]+$/.test(nombre)) || (apellido && !/^[a-zA-Z]+$/.test(apellido))) {
                errors.push('El nombre y el apellido solo deben contener letras.');
            }

            if (errors.length > 0) {
                $('#validationErrors').removeClass('d-none').html(errors.join('<br>'));
                return false;
            }

            $('#validationErrors').addClass('d-none').text('');
            return true;
        }

        function saveUser(event) {
            if (!validateForm()) {
                return; // Detener la ejecución si el formulario no es válido
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: $('#fm').serialize(),
                success: function (result) {
                    try {
                        var parsedResult = JSON.parse(result);
                        if (parsedResult.success) {
                            $('#dlg').modal('hide'); // Cerrar el modal
                            cargarTabla(); // Actualizar la tabla
                        } else {
                            alert(parsedResult.message);
                        }
                    } catch (e) {
                        console.error("Error al parsear la respuesta del servidor: ", e);
                        console.error("Respuesta del servidor: ", result);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("Error en la solicitud: " + textStatus + " - " + errorThrown);
                    console.error("Respuesta del servidor: ", jqXHR.responseText);
                }
            });
        }

        function destroyUser() {
            var row = $('#dg tbody').find('tr.selected');
            if (row.length > 0) {
                var cedula = row.find('td:eq(0)').text();
                if (confirm('¿Está seguro que desea eliminar a este empleado?')) {
                    $.ajax({
                        url: 'models/borrar.php',
                        type: 'POST',
                        data: { cedula: cedula },
                        success: function (result) {
                            if (result.success) {
                                cargarTabla();
                            } else {
                                alert(result.errorMsg);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert('Error en la solicitud: ' + textStatus + ' - ' + errorThrown);
                        }
                    });
                }
            }
        }

        function cargarTabla() {
            $.ajax({
                url: 'models/acceder.php',
                dataType: 'json',
                success: function (data) {
                    var tbody = $('#dg tbody');
                    tbody.empty();
                    $.each(data, function (index, item) {
                        var row = '<tr>' +
                            '<td>' + item.cedula + '</td>' +
                            '<td>' + item.nombre + '</td>' +
                            '<td>' + item.apellido + '</td>' +
                            '<td>' + item.telefono + '</td>' +
                            '<td>' + item.direccion + '</td>' +
                            '<td>' + item.hora_entrada_mnn + '-' + item.hora_salida_mnn + '</td>' +
                            '<td>' + item.hora_entrada_tarde + '-' + item.hora_salida_tarde + '</td>' +
                            '<td><button class="btn btn-sm btn-info" onclick="editUser()">Editar</button> ' +
                            '<button class="btn btn-sm btn-danger" onclick="destroyUser()">Eliminar</button></td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Error en la solicitud: " + textStatus + " - " + errorThrown);
                }
            });
        }

        $(document).ready(function () {
            cargarTabla();
            $('#fm').on('submit', saveUser);

            $('#dg tbody').on('click', 'tr', function () {
                $(this).toggleClass('selected').siblings().removeClass('selected');
            });

            $('input[name="emp_telefono"]').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });  // Enlazar el evento de guardado del formulario
        });
    </script>
</body>

</html>
