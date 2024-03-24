(function(){
    const ponentesInput = document.querySelector('#ponentes');
    if(ponentesInput){
        let ponentes = []; //guardará los ponentes de la base de datos
        let ponentesFiltrados = [];
        
        const listadoPonentes = document.querySelector('#listado-ponentes'); //selecciona el ul para inyectar ponentes filtrados
        const ponenteHidden = document.querySelector('[name="ponente_id"]'); //selecciona el input hidden para ponente seleccionado
        obtenerPonentes(); //obtiene globalmente los ponentes formateados

        ponentesInput.addEventListener('input', buscarPonentes); //lee el input de ponentes
        /*actualzar ponente*/
        if(ponenteHidden.value){ //si el value de hidden ponente tiene un valor...
            (async()=>{
                const ponente = await obtenerPonente(ponenteHidden.value);
                
                //insertar en el html
                const ponenteDOM = document.createElement('LI');
                ponenteDOM.classList.add('listado-ponentes__ponente', 'listado-ponentes__ponente--seleccionado'); //agrega clase de seleccionado
                ponenteDOM.textContent = `${ponente.nombre} ${ponente.apellido}`;

                listadoPonentes.appendChild(ponenteDOM); //inserta en el listado de ponentes
            })();
        }
        //obtiene todos los ponentes para mostrarlos
        async function obtenerPonentes(){
            const url = `${location.origin}/api/ponentes`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json(); //arreglo de objetos
            formatearPonentes(resultado); 
        }
        //obtiene un ponente para actualizarlo
        async function obtenerPonente(id){ //id del input hidden
            const url = `${location.origin}/api/ponente?id=${id}`;
            const respuesta = await fetch(url);

            const resultado = await respuesta.json();
            return resultado;
        }

        //toma el arreglo de ponentes y los mapea para formatearlos
        function formatearPonentes(arrayPonentes = []){
            ponentes = arrayPonentes.map(ponente => { //mapea el arreglo. a cada objeto le aplica lo sig:
                return{ //objeto
                    nombre: `${ponente.nombre.trim()} ${ponente.apellido.trim()}`,
                    id: ponente.id
                }
            });
            // console.log(ponentes); //imprime formateados los ponentes
        }

        function buscarPonentes(e){
            const busqueda = e.target.value; //lee el input de ponentes

            if(busqueda.length > 3){ //si se han tecleado mas de 3 letras
                const expresion = new RegExp(busqueda, "i");
                ponentesFiltrados = ponentes.filter(ponente => { //filtra los ponentes que cumplan con
                    if(ponente.nombre.toLowerCase().search(expresion) != -1){ //que no retornen -1 (no coincidan)
                        return ponente; //retorna a ponentesFiltrados
                    }
                });
            }else{
                ponentesFiltrados = [];
            }
            mostrarPonentes();
        }
        function mostrarPonentes(){
            // listadoPonentes.innerHTML = '';
            while(listadoPonentes.firstChild){
                listadoPonentes.removeChild(listadoPonentes.firstChild);
            }
            if(ponentesFiltrados.length === 0){ //si los ponentes filtrados está vacío
                const noResultados = document.createElement('P');
                noResultados.classList.add('listado-ponentes--no-resultado');
                noResultados.textContent = 'No hay resultados para tu búsqueda';
                listadoPonentes.appendChild(noResultados);
            }
            ponentesFiltrados.forEach(ponente =>{ //crea html por cada ponente filtrado
                const ponenteHTML = document.createElement('LI'); //crea un elemento de lista
                ponenteHTML.classList.add('listado-ponentes__ponente'); //le agrega la clase listadoponenteponente
                ponenteHTML.textContent = ponente.nombre; //agrega en texto el ponente filtrado
                ponenteHTML.dataset.ponenteId = ponente.id; //agrega el atributo personalizado con ponente.id
                ponenteHTML.onclick = seleccionarPonente;

                //añadir al dom
                listadoPonentes.appendChild(ponenteHTML);
            });
        }

        function seleccionarPonente(e){
            const ponente = e.target;

            //remover la clase previa
            const ponentePrevio = document.querySelector('.listado-ponentes__ponente--seleccionado');
            if(ponentePrevio){
                ponentePrevio.classList.remove('listado-ponentes__ponente--seleccionado');
            }
            
            ponente.classList.add('listado-ponentes__ponente--seleccionado');

            ponenteHidden.value = ponente.dataset.ponenteId; //asigna el id de ponente al value del hidden
        }
    }
})();