<main class="devwebcamp">
    <h2 class="devwebcamp__heading"><?php echo $titulo; ?></h2>
    <p class="devwebcamp__descripcion">Conoce la Conferencia mas importante de Latinoamérica</p>

    <div class="devwebcamp__grid">
        <div <?php aos_animacion(); ?> class="devwebcamp__imagen">
            <picture>
                <source srcset="build/img/sobre_devwebcamp.avif" type="image/avif">
                <source srcset="build/img/sobre_devwebcamp.webp" type="image/webp">
                <img src="build/img/sobre_devwebcamp.jpg" loading="lazy" width="200" height="300" alt="Imagen devwebcamp">
            </picture>
        </div>

        <div class="devwebcamp__contenido">
            <p class="devwebcamp__texto">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Error magni veniam odit. Atque dolores hic iste optio beatae id provident odit deserunt, numquam, nisi, tenetur sint. Incidunt iure at commodi.
            </p>
            <p class="devwebcamp__texto">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Error magni veniam odit. Atque dolores hic iste optio beatae id provident odit deserunt, numquam, nisi, tenetur sint. Incidunt iure at commodi.
            </p>
        </div>
    </div>
</main>