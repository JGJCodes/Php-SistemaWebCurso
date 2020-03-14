<?php
//Mostrar los mesajes informativos de los procesos
    if(isset($messages)){
        ?> <!-- Si existen mensajes por mostrar se genera un contenedor para imprimirlos en la pagina -->
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>¡Bien hecho!</strong>
                <?php //impresion de los mensajes
                    foreach($messages as $message){
                        echo $message;
                    }
                ?>
        </div>
        <?php
    }//Cierre del IF messages
