<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Personal</legend>

    <div class="formulario__campo">
        <label for="nombre" class="formulario_label">Nombre</label>
        <input 
            type="text" 
            class="formulario__input" 
            id="nombre" 
            name="nombre" 
            placeholder="Nombre del Ponente" 
            value="<?php echo $ponente->nombre ?? ''; ?>"
            />
    </div>

    <div class="formulario__campo">
        <label for="apellido" class="formulario_label">Apellido</label>
        <input 
            type="text" 
            class="formulario__input" 
            id="apellido" 
            name="apellido" 
            placeholder="Apellido del Ponente" 
            value="<?php echo $ponente->apellido ?? ''; ?>"
            />
    </div>
    <div class="formulario__campo">
        <label for="ciudad" class="formulario_label">Ciudad</label>
        <input 
            type="text" 
            class="formulario__input" 
            id="ciudad" 
            name="ciudad" 
            placeholder="Ciudad del Ponente" 
            value="<?php echo $ponente->ciudad ?? ''; ?>"
            />
    </div>
    <div class="formulario__campo">
        <label for="pais" class="formulario_label">País</label>
        <input 
            type="text" 
            class="formulario__input" 
            id="pais" 
            name="pais" 
            placeholder="País del Ponente" 
            value="<?php echo $ponente->pais ?? ''; ?>"
            />
    </div>
    <div class="formulario__campo">
        <label for="imagen" class="formulario_label">Imagen</label>
        <input 
            type="file" 
            class="formulario__input formulario__input--file" 
            id="imagen" 
            name="imagen" 
            />
    </div>
    <?php if(isset($ponente->imagen_actual)){ ?>
        <p class="formulario__texto">Imagen Actual:</p>
        <div class="formulario__imagen">
            <picture>   
                    <source srcset="<?php echo $_ENV['HOST'] . '/img/speakers/' . $ponente->imagen; ?>.webp" type="image/webp">
                    <source srcset="<?php echo $_ENV['HOST'] . '/img/speakers/' . $ponente->imagen; ?>.png" type="image/png">
                    <img src="<?php echo $_ENV['HOST'] . '/img/speakers/' . $ponente->imagen; ?>.png" alt="Imagen del ponente">
            </picture>
        </div>

    <?php } ?>
    
</fieldset>
<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Extra</legend>
    <div class="formulario__campo">
        <label for="pais" class="formulario_label">Áreas de Experiencia (separadas por coma)</label>
        <input 
            type="text" 
            class="formulario__input" 
            id="tags_input"  
            placeholder="Ej. Node.js, PHP, Laravel, PHP, CSS, UX / UI" 
        /> 
        <div class="formulario__listado" id="tags"></div>
        <input type="hidden" name="tags" value="<?php echo $ponente->tags ?? ''; ?>">
    </div>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Redes Sociales</legend>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono"> <!--Contiene un icono y el input text-->
            <div class="formulario__icono">
                <i class="fa-brands fa-facebook"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales" 
                name="redes[facebook]"
                placeholder="Facebook" 
                value="<?php echo $redes->facebook ?? ''; ?>"
            />
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono"> <!--Contiene un icono y el input text-->
            <div class="formulario__icono">
                <i class="fa-brands fa-twitter"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales" 
                name="redes[twitter]"
                placeholder="Twitter" 
                value="<?php echo $redes->twitter ?? ''; ?>"
            />
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono"> <!--Contiene un icono y el input text-->
            <div class="formulario__icono">
                <i class="fa-brands fa-youtube"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales" 
                name="redes[youtube]"
                placeholder="Yooutube" 
                value="<?php echo $redes->youtube ?? ''; ?>"
            />
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono"> <!--Contiene un icono y el input text-->
            <div class="formulario__icono">
                <i class="fa-brands fa-instagram"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales" 
                name="redes[instagram]"
                placeholder="Instagram" 
                value="<?php echo $redes->instagram ?? ''; ?>"
            />
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono"> <!--Contiene un icono y el input text-->
            <div class="formulario__icono">
                <i class="fa-brands fa-tiktok"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales" 
                name="redes[tiktok]"
                placeholder="Tiktok" 
                value="<?php echo $redes->tiktok ?? ''; ?>"
            />
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono"> <!--Contiene un icono y el input text-->
            <div class="formulario__icono">
                <i class="fa-brands fa-github"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales" 
                name="redes[github]"
                placeholder="GitHub" 
                value="<?php echo $redes->github ?? ''; ?>"
            />
        </div>
    </div>
    
</fieldset>