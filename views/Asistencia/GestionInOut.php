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
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
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


    <script>
        const btnSubmit = document.getElementById('btn_submit');
        const cedula = "<?php echo $email; ?>";
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'long' };
            const dateTimeString = now.toLocaleDateString('es-EC', options) + ', ' + now.toLocaleTimeString();
            document.getElementById('fecha').innerText = dateTimeString;
        }

        function initializePage() {
            updateDateTime();
            //   const cedulaEmpleado = '2222222222';

            const formData = new FormData();
            formData.append('cedula', cedula);

            const fetchOptions = {
                method: 'POST',
                body: formData
            };
            fetch('./app/Models/obtener_horas.php', fetchOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener las horas de entrada y salida');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    document.getElementById('nombreUser').textContent = data.nombre + " " + data.apellido;
                    document.getElementById('hora_entrada_mnn').textContent = data.hora_entrada_mnn;
                    document.getElementById('hora_salida_mnn').textContent = data.hora_salida_mnn;
                    document.getElementById('hora_entrada_tarde').textContent = data.hora_entrada_tarde;
                    document.getElementById('hora_salida_tarde').textContent = data.hora_salida_tarde;


                    document.getElementById('hora_entrada_mnn').dataset.value = data.hora_entrada_mnn;
                    document.getElementById('hora_salida_mnn').dataset.value = data.hora_salida_mnn;
                    document.getElementById('hora_entrada_tarde').dataset.value = data.hora_entrada_tarde;
                    document.getElementById('hora_salida_tarde').dataset.value = data.hora_salida_tarde;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }



        function registrarEntrada() {

        }
        function consultaAsistencia() {
            // const cedula = '2222222222'; // Aquí debes obtener la cédula del empleado de alguna manera segura
            const fecha_actual = obtenerFechaActual(); // Función para obtener la fecha actual en el formato necesario (ej. '2024-06-16')
            console.log("fecha act cosnulta" + fecha_actual)
            const formData = new FormData();
            formData.append('cedula', cedula);
            formData.append('fecha_actual', fecha_actual);

            const fetchOptions = {
                method: 'POST',
                body: formData
            };

            fetch('./app/Models/consulta_asistencia.php', fetchOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener las horas de entrada y salida');
                    }
                    return response.json();
                })
                .then(data => {
                    // Actualizar las horas de entrada y salida en la tabla según los datos recibidos
                    document.getElementById('registro_entrada_mnn').textContent = data.hora_entrada_mnn || 'N/A';
                    document.getElementById('registro_salida_mnn').textContent = data.hora_salida_mnn || 'N/A';
                    document.getElementById('registro_entrada_tarde').textContent = data.hora_entrada_tarde || 'N/A';
                    document.getElementById('registro_salida_tarde').textContent = data.hora_salida_tarde || 'N/A';

                    document.getElementById('registro_entrada_mnn').dataset.value = data.hora_entrada_mnn || 'N/A';
                    document.getElementById('registro_salida_mnn').dataset.value = data.hora_salida_mnn || 'N/A';
                    document.getElementById('registro_entrada_tarde').dataset.value = data.hora_entrada_tarde || 'N/A';
                    document.getElementById('registro_salida_tarde').dataset.value = data.hora_salida_tarde || 'N/A';
                    setTimeout(() => {
                        comportamiento();
                    }, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }


        function obtenerFechaActual() {
            const now = new Date();
            const year = now.getFullYear();
            let month = now.getMonth() + 1;
            let day = now.getDate();

            // Ajustar el formato a 'YYYY-MM-DD'
            month = month < 10 ? '0' + month : month;
            day = day < 10 ? '0' + day : day;

            return `${year}-${month}-${day}`;
        }
        initializePage();
        consultaAsistencia();

        // Actualización continua de la fecha y hora
        setInterval(updateDateTime, 1000);


        function comportamiento() {
            const entradaMnnValue = document.getElementById('registro_entrada_mnn').dataset.value;
            const salidaMnnValue = document.getElementById('registro_salida_mnn').dataset.value;
            const horaEntradaMnnValue = document.getElementById('hora_entrada_mnn').dataset.value;
            const horaSalidaMnnValue = document.getElementById('hora_salida_mnn').dataset.value;

            const entradaTardeValue = document.getElementById('registro_entrada_tarde').dataset.value;
            const salidaTardeValue = document.getElementById('registro_salida_tarde').dataset.value;
            const horaEntradaTardeValue = document.getElementById('hora_entrada_tarde').dataset.value;
            const horaSalidaTardeValue = document.getElementById('hora_salida_tarde').dataset.value;
            console.log(horaEntradaTardeValue);
            // Obtener la hora actual
            const horaActual = new Date();
            console.log(horaActual);
            const horaActualStr = horaActual.toLocaleTimeString('es-EC', { hour12: false });

            // Convertir la hora actual a formato HH:mm para comparaciones
            const horaActualHHMM = pad(horaActual.getHours()) + ':' + pad(horaActual.getMinutes());
            let horaEntradaMnnDate = new Date('2000-01-01T' + horaEntradaMnnValue); // Utilizamos una fecha ficticia para facilitar la manipulación
            horaEntradaMnnDate.setMinutes(horaEntradaMnnDate.getMinutes() - 15);

            // Formatear la nueva hora en formato HH:mm
            const horaMenos15Minutos = pad(horaEntradaMnnDate.getHours()) + ':' + pad(horaEntradaMnnDate.getMinutes());
            let horaSalidaMnnDate = new Date('2000-01-01T' + horaSalidaMnnValue); // Utilizamos una fecha ficticia para facilitar la manipulación
            horaSalidaMnnDate.setMinutes(horaSalidaMnnDate.getMinutes() + 10);
            const horaSalidaMas10Minutos = pad(horaSalidaMnnDate.getHours()) + ':' + pad(horaSalidaMnnDate.getMinutes());

            // Función para añadir ceros a la izquierda si es necesario
            function pad(number) {
                if (number < 10) {
                    return '0' + number;
                }
                return number.toString();
            }

            let horaEntradaTardeDate = new Date('2000-01-01T' + horaEntradaTardeValue); // Utilizamos una fecha ficticia para facilitar la manipulación
            horaEntradaTardeDate.setMinutes(horaEntradaTardeDate.getMinutes() - 15);
            // Formatear la nueva hora en formato HH:mm
            const horaEntradaTardeMenos15Minutos = pad(horaEntradaTardeDate.getHours()) + ':' + pad(horaEntradaTardeDate.getMinutes());
            console.log(horaEntradaTardeMenos15Minutos);

            let horaSalidaTardeDate = new Date('2000-01-01T' + horaSalidaTardeValue);
            horaSalidaTardeDate.setMinutes(horaSalidaTardeDate.getMinutes() + 10);

            // Formatear la nueva hora en formato HH:mm
            const horaSalidaTardeMas10Minutos = pad(horaSalidaTardeDate.getHours()) + ':' + pad(horaSalidaTardeDate.getMinutes());
            console.log(horaActualStr < horaSalidaMas10Minutos);


            if (horaActual.getHours() >= 6 && horaActual.getHours() <= 13 && horaActualStr < horaSalidaMas10Minutos) { // 14 representa las 2 pm en formato de 24 horas
                console.log();
                if (entradaMnnValue === 'N/A' && horaActualStr >= horaMenos15Minutos && horaActualStr < horaSalidaMas10Minutos) {
                    console.log(entradaMnnValue + " " + horaActualStr + " " + horaMenos15Minutos + " " + horaSalidaMnnValue + " ");
                    document.getElementById('btn_submit').textContent = 'Registrar entrada';
                    document.getElementById('btn_submit').value = "EntradaManana";

                    return;
                } else if (entradaMnnValue !== "N/A" && salidaMnnValue === 'N/A' && horaActualHHMM < horaSalidaMas10Minutos) { /// } else if (salidaTardeValue === 'N/A' &&  horaActualHHMM < horaSalidaTardeMas10Minutos && horaActualHHMM>=horaSalidaTardeValue) {
                    console.log(salidaMnnValue + " " + horaActualHHMM + " " + horaEntradaMnnValue + " " + horaSalidaMas10Minutos + " ");

                    document.getElementById('btn_submit').textContent = 'Registrar salida';
                    document.getElementById('btn_submit').value = "SalidaManana";

                    return;
                } else {
                    document.getElementById('btn_submit').disabled = true;
                    document.getElementById('btn_submit').textContent = 'Acción no disponible';
                    return;
                }

            } else if (horaActual.getHours() >= 13 && horaActual.getHours() < 21) { // 21 representa las 9 pm en formato de 24 horas
                if (entradaTardeValue === 'N/A' && horaActualHHMM < horaSalidaTardeMas10Minutos && horaActualStr >= horaEntradaTardeMenos15Minutos  ) {
                    console.log("dentro de reg entrad");

                    document.getElementById('btn_submit').textContent = 'Registrar entrada';
                    document.getElementById('btn_submit').value = "EntradaTarde";
                    return;
                } else if (entradaTardeValue !== 'N/A' && salidaTardeValue === 'N/A' && horaActualHHMM < horaSalidaTardeMas10Minutos) {  //  si es necesario bloquear el boton de salida hasta que sea la hora         } else if (salidaTardeValue === 'N/A' &&  horaActualHHMM < horaSalidaTardeMas10Minutos && horaActualHHMM>=horaSalidaTardeValue) {

                    console.log(salidaTardeValue + " asxx" + "  as" + horaActualHHMM + "  zz" + horaEntradaTardeValue);

                    document.getElementById('btn_submit').textContent = 'Registrar salida';
                    document.getElementById('btn_submit').value = "SalidaTarde";

                    return;
                } else {
                    document.getElementById('btn_submit').disabled = true;
                    document.getElementById('btn_submit').textContent = 'Acción no disponible';
                    console.log(salidaTardeValue + "  " + horaActualHHMM + "   " + horaSalidaTardeMas10Minutos + "  " + horaSalidaTardeValue);

                    return;
                }

            } else {
                document.getElementById('btn_submit').disabled = true;
                document.getElementById('btn_submit').textContent = 'Fuera de horario';
                document.getElementById('btn_submit').style.backgroundColor = 'grey';

                return;
            }
        }

        function enviarRegistro(tipoRegistro, hora, cedula) {
            const urlServidor = './app/Models/guardar_asistencia.php';
            return new Promise((resolve, reject) => {

                // Datos a enviar
                const datos = {
                    tipoRegistro: tipoRegistro,
                    hora: hora,
                    cedulaEmpleado: cedula
                };

                // Realizar la solicitud fetch
                fetch(urlServidor, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(datos)
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.message);
                        }
                        return response.json();
                    })
                    .then(data => {
                        resolve();

                        console.log('Registro enviado correctamente:', data);
                    })
                    .catch(error => {
                        console.error('Error al enviar el registro:', error);
                    });
            });

        }
        function obtenerHoraActual() {
            const ahora = new Date();
            const horas = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0'); //

            const horaActual = `${horas}:${minutos}:${segundos}`;
            return horaActual;
        }


        btnSubmit.addEventListener('click', function () {
            const valorBoton = btnSubmit.value;
            const hora = obtenerHoraActual();
            //   const cedulaEmpleado = '2222222222';
            //  const hora = obtenerHoraActual();
            const horaEntradaMnnValue = document.getElementById('hora_entrada_mnn').dataset.value;
            const entradaMnnValue = document.getElementById('registro_entrada_mnn').dataset.value;
            const salidaMnnValue = document.getElementById('registro_salida_mnn').dataset.value;
            const horaEntradatardeValue = document.getElementById('hora_entrada_tarde').dataset.value;
            const horasalidatardeValue = document.getElementById('hora_salida_tarde').dataset.value;
            const entradaTardeValue = document.getElementById('registro_entrada_tarde').dataset.value;
            const horaSalidaMnnValue = document.getElementById('hora_salida_mnn').dataset.value;
            function pad(number) {
                if (number < 10) {
                    return '0' + number;
                }
                return number.toString();
            }
            let horaSalidaMnnDate = new Date('2000-01-01T' + horaSalidaMnnValue); // Utilizamos una fecha ficticia para facilitar la manipulación
            horaSalidaMnnDate.setMinutes(horaSalidaMnnDate.getMinutes() + 10);
            const horaSalidaMas10Minutos = pad(horaSalidaMnnDate.getHours()) + ':' + pad(horaSalidaMnnDate.getMinutes());
            switch (valorBoton) {
                case 'EntradaManana':
                    enviarRegistro('entradaManana', hora, cedula)
                        .then(() => {
                            if (hora > horaEntradaMnnValue) {
                                const multa = calcularMulta(hora, horaEntradaMnnValue);
                                return enviarmulta(multa, "Atraso", cedula);

                            } else {
                                window.location.reload();

                                return Promise.resolve();
                            }
                        })
                        .catch(error => {
                            console.error("Error durante el proceso:", error);
                        });
                    break;
                case 'SalidaManana':
                    enviarRegistro('salidaManana', hora, cedula)
                        .then(() => {
                            if (hora < horaSalidaMnnValue) {
                                const multa = calcularMulta(horaSalidaMnnValue, hora);
                                return enviarmulta(multa, "Salida temprana", cedula);
                            } else {
                                window.location.reload();

                                return Promise.resolve();

                            }
                        })
                        .catch(error => {
                            console.error("Error durante el proceso:", error);
                        });

                    //  enviarRegistro('salidaManana', hora, cedulaEmpleado);
                    break;
                case 'EntradaTarde':
                    enviarRegistro('entradaTarde', hora, cedula)
                        .then(() => {
                            if (salidaMnnValue == "N/A" || entradaMnnValue == "N/A") { //
                                const mult = 8 * 8;
                                enviarmulta((mult), "Inasistencia", cedula);

                            } else {
                                if (hora > horaEntradatardeValue) {
                                    const multa = calcularMulta(hora, horaEntradatardeValue);
                                    return enviarmulta(multa, "Atraso", cedula);

                                } else {
                                    window.location.reload();

                                    return Promise.resolve();

                                }
                                window.location.reload();

                            }
                        })
                        .catch(error => {
                            console.error("Error durante el proceso:", error);
                        });
                    //      enviarRegistro('salidaTarde', hora, cedulaEmpleado);
                    break;
                case 'SalidaTarde':
                    enviarRegistro('salidaTarde', hora, cedula)
                        .then(() => {
                            if (salidaMnnValue == "N/A" || entradaMnnValue == "N/A" || entradaTardeValue == "N/A") { //
                                const mult = 8 * 8;
                                enviarmulta((mult), "Inasistencia", cedula);
                            } else {
                                if (hora < horasalidatardeValue) {
                                    const multa = calcularMulta(horasalidatardeValue, hora);
                                    return enviarmulta(multa, "Salida Temprana", cedula);

                                } else {

                                    return Promise.resolve();

                                }
                                window.location.reload();

                            }
                        })
                        .catch(error => {
                            console.error("Error durante el proceso:", error);
                        });
                    break;

                default:
                    console.log('Acción no definida para este botón.', valorBoton);
                    break;
            }
        });
        function convertirASegundos(hora) {
            const [h, m, s] = hora.split(':').map(Number);
            return h * 3600 + m * 60 + s;
        }

        function convertirAMinutos(segundos) {
            return Math.floor(segundos / 60);
        }
        function calcularMulta(hora1, hora2) {
            const segundos1 = convertirASegundos(hora1);
            const segundos2 = convertirASegundos(hora2);
            let diferenciaSegundos = segundos1 - segundos2;

            // Asegúrate de que la diferencia no sea negativa
            if (diferenciaSegundos < 0) {
                diferenciaSegundos = Math.abs(diferenciaSegundos);
            }

            // Convertir la diferencia en minutos
            const diferenciaMinutos = convertirAMinutos(diferenciaSegundos);

            // Calcular la multa
            const multa = diferenciaMinutos * 0.25;
            return multa;
        }

        // Función auxiliar para convertir horas en segundos
        function convertirASegundos(hora) {
            const partes = hora.split(':');
            const horas = parseInt(partes[0], 10);
            const minutos = parseInt(partes[1], 10);
            return horas * 3600 + minutos * 60;
        }

        // Función auxiliar para convertir segundos en minutos
        function convertirAMinutos(segundos) {
            return Math.round(segundos / 60); // Redondear a minutos
        }

        function enviarmulta(valor, tipomulta, cedula) {
            const url = './app/Models/multa.php';
            const data = {
                valor: valor,
                tipomulta: tipomulta,
                cedulaEmpleado: cedula
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Multa enviada correctamente:', data);
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error al enviar la multa:', error);
                });
        }

    </script>
</body>

</html>