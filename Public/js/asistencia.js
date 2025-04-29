document.addEventListener('DOMContentLoaded', () => {

    const btnSubmit = document.getElementById('btn_submit');
    const cedula = window.CEDULA_EMPLEADO;
    console.log(cedula);
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long' };
        const dateTimeString = now.toLocaleDateString('es-EC', options) + ', ' + now.toLocaleTimeString();
        document.getElementById('fecha').innerText = dateTimeString;
    }

    function initializePage() {
        updateDateTime();

        const formData = new FormData();
        formData.append('cedula', cedula);
        formData.append("action", "obtenerHoras",);
        const fetchOptions = {
            method: 'POST',
            body: formData
        };
        fetch('./app/Controllers/AsistenciaController.php', fetchOptions)
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
        formData.append("action", "consultarAsistencia",);
        const fetchOptions = {
            method: 'POST',
            body: formData,

        };

        fetch('./app/Controllers/AsistenciaController.php', fetchOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener las horas de entrada y salida');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
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
            if (entradaTardeValue === 'N/A' && horaActualHHMM < horaSalidaTardeMas10Minutos && horaActualStr >= horaEntradaTardeMenos15Minutos) {
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
        const urlServidor = './app/Controllers/AsistenciaController.php';
        return new Promise((resolve, reject) => {

            const datos = {
                tipoRegistro: tipoRegistro,
                hora: hora,
                cedulaEmpleado: cedula,
                action: "registrarAsistencia"

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
        const url = './app/Controllers/MultaController.php';
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

});
