<?php

    foreach($alertas as $key => $alerta){ //se accede al key y al value
        foreach($alerta as $mensaje){
?>
            <div class="alerta alerta__<?php echo $key; ?>"><?php echo $mensaje; ?> </div>
<?php   }
    }
    ?>