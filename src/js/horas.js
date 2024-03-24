(function(){
    //el codigo se ejecuta solo si está disponibles las categorias o el de los dias o el de las horas
    const horas = document.querySelector('#horas');
    if(horas){
        
        const categoria = document.querySelector('[name="categoria_id"]'); //selecciona el input select
        const dias = document.querySelectorAll('[name="dia"]'); //selecciona el select radio
        const inputHiddenDia = document.querySelector('[name = "dia_id"]'); //selecciona el input hidden
        const inputHiddenHora = document.querySelector('[name = "hora_id"]'); //selecciona el input hidden para inyectar las horas
        
        //espera  cambio de categoria y dia
        categoria.addEventListener('change', terminoBusqueda);
        dias.forEach(dias => dias.addEventListener('change', terminoBusqueda)); //espera por un cambio en el select radio

        // console.log(categoria.value);
        let busqueda = { //objeto con los name de categoria y dia
            categoria_id: +categoria.value || '', //valor del value de categoria_id o nada(nuevo registro)
            dia: +inputHiddenDia.value || ''
        }
        
        /* actualizar */ 
        if(!Object.values(busqueda).includes('')){ //al actualizar, busqueda no debe estar vacío y consulta la API
            async function iniciarApp(){
                await buscarEventos(); //el codigo que sigue no se ejecutar
                //resaltar la hora del evento a actualizar
                const id = inputHiddenHora.value; //en el formulario se le coloca hora_id al value de input hidden hora
                const horaSeleccionada = document.querySelector(`[data-hora-id = "${id}"]`); //selecciona el li de la hora seleccionada
            
                horaSeleccionada.classList.remove('horas__hora--deshabilitada');
                horaSeleccionada.classList.add('horas__hora--seleccionada');
                horaSeleccionada.onclick = seleccionarHora;
            }
            
            iniciarApp();
        }
        
        //funcion para asignar al value de input hidden y guarda en el objeto
        function terminoBusqueda(e){ //funcion que pasa el evento de cambio de estado del select o radio
            // inputHiddenDia.value = e.target.value; //copia el valor de id de dia (1 o 2) al value del input hidden 
            busqueda[e.target.name] =  e.target.value; //asigna a busqueda[dia] el valor seleccionado del input radio

            //reiniciar los campos ocultos y el selector de horas
            inputHiddenDia.value = '';
            inputHiddenHora.value = '';

            const horaPrevia = document.querySelector('.horas__hora--seleccionada');
            if(horaPrevia){
                horaPrevia.classList.remove('horas__hora--seleccionada');
            } //si existe la clase de seleccionada, se borra.
            if(Object.values(busqueda).includes('')){ //si está vacío sale de la función
                return;
            }
            buscarEventos(); //si busqueda tiene valores, busca los eventos
        }
        //conecta con api para obtener eventos
        async function buscarEventos(){
            const {dia, categoria_id} = busqueda; //destructuring a busqueda
            const url = `/api/eventos-horario?dia_id=${dia}&categoria_id=${categoria_id}`; //template string. Asigna busqueda al query string
            const resultado = await fetch(url); //hace la cnsulta usando a url
            const eventos = await resultado.json(); //hace consulta en el resultado de la consulta de Eventos
            //console.log(eventos); //imprime los eventos ya seleccionados presentes en la base de datos
            obtenerHorasDisponibles(eventos);   
        }
        
        function obtenerHorasDisponibles(eventos){
            //reiniciar las horas
            const listadoHoras = document.querySelectorAll('#horas li'); //selecciona todos los elementos li
            listadoHoras.forEach(li => li.classList.add('horas__hora--deshabilitada'));

            //Comprobar eventos ya tomados y quitar la variable de deshabilitar
            const horasTomadas = eventos.map(evento => evento.hora_id); //asigna los dia_id de los elementos de la db
            
            const listadoHorasArray = Array.from(listadoHoras);
            // console.log(listadoHorasArray);
            const resultado = listadoHorasArray.filter(li => !horasTomadas.includes(li.dataset.horaId)); //filtra el listado con las horas no tomadas
            resultado.forEach(li => li.classList.remove('horas__hora--deshabilitada'));
            
            //asigna un evento a los horarios
            const horasDisponibles = document.querySelectorAll('#horas li:not(.horas__hora--deshabilitada)');
            horasDisponibles.forEach(hora => hora.addEventListener('click', seleccionarHora));
        }
        //funcion asignada al dar clic a las horas
        function seleccionarHora(e){
            //elimina la clase si ya existe
            const horaPrevia = document.querySelector('.horas__hora--seleccionada');
            if(horaPrevia){
                horaPrevia.classList.remove('horas__hora--seleccionada')
            }
            //Agregar clase de seleccionado
            e.target.classList.add('horas__hora--seleccionada'); //agrega la clase
            inputHiddenHora.value = e.target.dataset.horaId; //asigna al value del input hidden el valor de dia id

            //llenar el campo oculto de dia. Agrega el value de radio checado al value del input hidden de dia
            inputHiddenDia.value = document.querySelector('[name = "dia"]:checked').value;
        }
    }
})();