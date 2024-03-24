<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Coloca tu nueva contraseña</p>

    <?php require_once __DIR__ . '/../templates/alertas.php'; ?>

    <?php if($token_valido){?>
        <form method="POST" class="formulario">
            <div class="formulario__campo">
                <label for="password" class="formulario__label">Nueva Contraseña</label>
                <input type="password" class="formulario__input" placeholder="Tu Nueva Contraseña" id="password" name="password">
            </div>

            <input type="submit" class="formulario__submit" value="Guardar contraseña">
        </form>
    <?php } ?>
    <div class="acciones">
        <a class="acciones__enlace" href="/login">Ya tienes cuenta? Inicia sesión</a>
        <a class="acciones__enlace" href="/registro">¿Aún no tienes una cuenta? Obten una</a>
    </div>
</main>