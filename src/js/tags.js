(function(){
    const tagsInput = document.querySelector('#tags_input'); //selecciona el input de tags
    const tagsInputHidden = document.querySelector('[name="tags"]'); //selecciona el input hidden

    if(tagsInput){ //si existe el input se ejecuta el interior del if
        const tagsDiv = document.querySelector('#tags');
        let tags = [];

        //Recuperar del input oculto
        if(tagsInputHidden.value !== ''){   //si el valor del value del input hidden no está vacío
            tags = tagsInputHidden.value.split(',');
            mostrarTags();
        }
        //escuchar los cambios en el input
        tagsInput.addEventListener('keypress', guardarTag); //si se teclea en el input de tags

        function guardarTag(e){
            if(e.keyCode === 44){

                if(e.target.value === '' || e.target.value < 1){ //si se agrega un espacio en blanco 
                    return; //sale de la función
                }
                e.preventDefault(); //evita que la coma se quede en el input
                tags = [...tags, e.target.value.trim()]; //asigna a tags una copia de tags y valor del input sin espacios
                tagsInput.value = ''; // borra el contenido del input
                mostrarTags();
            }
        }
        function mostrarTags(){
            tagsDiv.textContent = '';
            tags.forEach(tag => { //por cada etiqueta
                const etiqueta = document.createElement('LI');
                etiqueta.classList.add('formulario__tag');
                etiqueta.textContent = tag; //agrega como texto la etiqueta actual
                etiqueta.ondblclick = eliminarTag; //asigna la funcion al evento doble clic
                tagsDiv.appendChild(etiqueta); 
            })
            actualizarInputHidden();
        }

        function eliminarTag(e){
            e.target.remove(); //elimina el tag del html
            tags = tags.filter(tag => tag !== e.target.textContent); //elimina el tag del arreglo
            actualizarInputHidden();
        }

        function actualizarInputHidden(){
            //agregar y eliminar tags del arreglo
            
            tagsInputHidden.value = tags.toString();
        }
    }
})(); //IIFE -> Expresion de funcion ejecutada inmediatamente