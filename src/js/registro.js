import Swal from 'sweetalert2';
(function(){
    let eventos = []; //aqui se guardan los eventos seleccionados globalmente

    const resumen = document.querySelector('#registro-resumen');
    if(resumen){
        const eventosBoton = document.querySelectorAll('.evento__agregar');
        eventosBoton.forEach(boton => boton.addEventListener('click', seleccionarEvento));

        //dar clic al boton Registrarme
        const formularioRegistro = document.querySelector('#registro');
        formularioRegistro.addEventListener('submit', submitFormulario);

        mostrarEventos(); //al inicio no se tienen eventos, se ejecuta el }else

        //dar clic al evento y obtener el id y la descripcion del html
        function seleccionarEvento(e){ //{target}
            
            if(eventos.length < 5){
                e.target.disabled = true;   //target.disabled = true
                eventos = [...eventos, { //se guarda a si mismo mas el nuevo id y titulo
                    id: e.target.dataset.id,    //id: target.dtaset.id
                    titulo: e.target.parentElement.querySelector('.evento__nombre').textContent.trim()
                }];        
                // console.log(eventos);
                mostrarEventos();
            }else{
                Swal.fire({
                    title: 'Error',
                    text: 'Máximo 5 eventos por registro',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        } 

        function mostrarEventos(){
            //limpiar HTML
            limpiarEventos();
            if(eventos.length > 0){ //si eventos tiene registros
                eventos.forEach(evento => {
                    const eventoDOM = document.createElement('DIV');
                    eventoDOM.classList.add('registro__evento');

                    const titulo = document.createElement('H3');
                    titulo.classList.add('registro__nombre');
                    titulo.textContent = evento.titulo;

                    //boton de eliminar
                    const botonEliminar = document.createElement('BUTTON');
                    botonEliminar.classList.add('registro__eliminar');
                    botonEliminar.innerHTML = '<i class = "fa-solid fa-trash"></li>'; //agrega el boton awesome
                    botonEliminar.onclick = function(){  //al dar click
                        eliminarEvento(evento.id); //llama a la funcion eliminarEvento y le pasa el id
                    }

                    //renderizar en el HTML
                    eventoDOM.appendChild(titulo); //inserta el ttulo en el DIV
                    eventoDOM.appendChild(botonEliminar);
                    resumen.appendChild(eventoDOM); //inserta el div en el primer DIV

                });
            }else{ //si el usuario no tiene eventos seleccionados
                const noRegistro = document.createElement('P');
                noRegistro.textContent = 'No hay eventos, añade hasta 5 del lado izquierdo';
                noRegistro.classList.add('registro__texto');
                resumen.appendChild(noRegistro);
            }
        }
        function eliminarEvento(id){
            eventos = eventos.filter(evento => evento.id !== id); //reescibe los eventos que no coincidad con el id argumento
            //volver seleccionable l evento
            const botonAgregar = document.querySelector(`[data-id = "${id}"]`);
            botonAgregar.disabled = false;

            mostrarEventos(); //vuelve a mostrar todos los eventos quitando el eliminado por el filter
        }
        function limpiarEventos(){
            while(resumen.firstChild){ //si tiene un elemento interno
                resumen.removeChild(resumen.firstChild); //eliminar los elementos hijos de resumen
            }
        }

        async function submitFormulario(e){
            e.preventDefault();

            //Regalo
            const regaloId = document.querySelector('#regalo').value; //extrae el valor del select
            const eventosId = eventos.map(evento => evento.id); //extrae el id de cada evento
            
            if( eventosId.length === 0 || regaloId === ''){ //si no selecciona regalo o eventos
                //alerta de sweetAlert
                Swal.fire({
                    title: 'Error',
                    text: 'Elige al menos un evento y un regalo.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                return; //sale de la funcion
            }
            //aqui se envia la información a la api
            // console.log('Registrando...');
            const datos = new FormData();
            datos.append('eventos', eventosId);
            datos.append('regalo_id', regaloId);

            const url = `${location.origin}/finalizar-registro/conferencias`;
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json(); //establece la conexion
            // console.log(resultado); //trae el resultado desde la API
            if (resultado.resultado){
                Swal.fire(
                    'Registro Exitoso',
                    'Tus conferencias se han almacenado y tu registro fue exitoso, te esperamos en DevWebCamp',
                    'success'
                ).then( () => location.href = `/boleto?id=${resultado.token}`);
            }else{
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(() =>  location.reload() );
            }
        }
    }
})(); //las variables no se mezclan con los otros archivos