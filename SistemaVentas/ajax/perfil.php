<?php
//importar la conexion de la BD y el modelo perfil
require_once("../config/conexion.php");
//require_once("../models/perfil.php");
require_once("../models/usuario.php");

$perfil=new Perfil();

/**declaramos las variables de los valores que se envian por el formulario y que recibimos por ajax 
 * y decimos que si existe el parametro que estamos recibiendo los valores vienen del atributo name
 * de los campos del formulario el valor id_usuario_perfil se carga en el campo hidden cuando se 
 * edita un registro declaracion de variables que vienen del formulario del perfil usuario 
 * **/
$id=isset($_POST["id_usuario"]);
$nombre=isset($_POST["nombre"]);
$apellido=isset($_POST["apellido"]);
$cedula=isset($_POST["cedula"]);
$telefono=isset($_POST["telefono"]);
$email=isset($_POST["email"]);
$direccion=isset($_POST["direccion"]);
$usuario=isset($_POST["usuario"]);
$pass1=isset($_POST["password1"]);
$pass2-=isset($_POST["password2"]);

switch($_GET["op"]){

    case 'mostrar_perfil':
                        //selecciona el id del usuario
                        $datos=$perfil->get_usuarioid($_POST["id_usuario"]);

                            // si existe el id del usuario entonces recorre el array
                        if(is_array($datos)==true and count($datos)>0){
                            foreach($datos as $row){
                                $output["cedula"] = $row["cedula"];
                                $output["nombre"] = $row["nombres"];
                                $output["apellido"] = $row["apellidos"];
                                $output["usuario_perfil"] = $row["usuario"];
                                $output["password1"] = $row["password"];
                                $output["password2"] = $row["password2"];
                                $output["telefono"] = $row["telefono"];
                                $output["correo"] = $row["correo"];
                                $output["direccion"] = $row["direccion"];
                            }
                            echo json_encode($output);
                        }else{
                                //si no existe el registro entonces no recorre el array
                                $errors[]="El usuario no existe";
                        }

                        require_once("../views/view_alertas.php");
                        /**inicio de mensaje de error
                        if (isset($errors)){
                            ?>
                            <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Error!</strong> 
                                <?php
                                foreach ($errors as $error) {
                                    echo $error;
                                    }
                                ?>
                            </div>
                            <?php
                            }
                            //fin de mensaje de error**/
                        break;


    case 'editar_perfil':
                        /**verificamos si el usuario existe en la base de datos, 
                        si ya existe un registro con la cedula, nombre o correo entonces no lo registra**/

                        $datos= $perfil->get_cedula_correo($_POST["cedula"], $_POST["email"]);

                        /**verificamos si el password1 coincide con el password2, si se cumple entonces 
                        verificamos si existe un registro con los datos enviados y en caso que no existe 
                        entonces se registra el usuario**/
                        if($_POST["password1"]==$_POST["password2"]){
                            if(is_array($datos)==true and count($datos)>0){
                                //si ya existe entonces editamos el usuario y sus permisos
                                $perfil->editar_perfil($id,$nombre,$apellido,$cedula,$telefono,$email,
                                                        $direccion,$usuario,$password1,$password2);
                                    $messages[]="El usuario se editó correctamente";
                                }//cierre condicional $datos
                            
                            }else { //cierre de condicional del password 
                                $errors[]="El password no coincide";
                            }

                            require_once("../views/view_mensajes.php");
                            require_once("../views/view_alertas.php");
                            /**mensaje success
                            if (isset($messages)){
                                ?>
                                <div class="alert alert-success" role="alert">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>¡Bien hecho!</strong>
                                    <?php
                                        foreach ($messages as $message) {
                                            echo $message;
                                        }
                                        ?>
                                </div>
                                <?php
                            }
                            //fin success

                            //mensaje error
                            if (isset($errors)){
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Error!</strong> 
                                    <?php
                                        foreach ($errors as $error) {
                                            echo $error;
                                        }
                                        ?>
                                </div>
                                <?php
                            }//fin mensaje error**/
                            break;


}


?>