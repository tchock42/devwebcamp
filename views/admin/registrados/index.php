<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor">
    <?php if(!empty($registros)){?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Nombres</th>
                    <th scope="col"  class="table__th">Email</th>
                    <th scope="col"  class="table__th">Plan</th>
                </tr>
            </thead>

            <tbody class="table__body">
                <?php foreach($registros as $registro){ ?> <!--por cada ponente realiza un bucle-->
                    <tr class="table__tr">
                        <td class="table__td">
                            <?php echo $registro->usuario->nombre . " " . $registro->usuario->apellido; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $registro->usuario->email; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $registro->paquete->nombre; ?>
                        </td>
                    </tr>
                    <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Registros Aún</p>
    <?php } ?>
</div>

<?php
    echo $paginacion; // se pasó en el render como 'paginacion' = $paginacion->paginacion()
?>