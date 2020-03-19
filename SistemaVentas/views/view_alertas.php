<?php
//Mostrar los mensajes de errores de las operaciones
    if(isset($errores)){
        ?> <!-- Si existen errores por mostrar se genera un contenedor para imprimirlos en la pagina -->
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>¡Error!</strong>
            <?php //impresion de los mensajes
                 foreach($errores as $error){
                    echo $error;
                 }
            ?>
        </div>
    <?php
    }//Cierre del IF errors

