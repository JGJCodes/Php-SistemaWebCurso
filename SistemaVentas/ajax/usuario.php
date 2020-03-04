<?php
//importar la conexion de la BD y el modelo usuario
require_once("../config/conexion.php");
require_once("../modelos/usuario.php");

$usuarios = new Usuarios();

//declaramos las variables de los valores recibidos por ajax
$id=isset($_POST["id_usuario"]);
$nombre=isset($_POST["nombre"]);
$apellido=isset($_POST["apellido"]);
$cedula=isset($_POST["cedula"]);
$telefono=isset($_POST["telefono"]);
$email=isset($_POST["email"]);
$direccion=isset($_POST["direccion"]);
$cargo=isset($_POST["cargo"]);
$usuario=isset($_POST["usuario"]);
$pass1=isset($_POST["password1"]);
$pass2-=isset($_POST["password2"]);
$estado=isset($_POST["estado"]); //es el que se envia del formulario

switch($_GET["op"]){
                            //verificamos si existe la cedula y correo en la BD
    case "guardaryeditar":$datos = $usuarios->get_cedula_correo($_POST["cedula"],$_POST["email"]);
                            if($pass1==$pass2){
                                //verificamos si no existe el id del usuario
                                if(empty($_POST["id_usuario"])){
                                    //verificamos si se recuperaron los datos del usuario
                                    if(is_array($datos)==true and count($datos)==0){
                                        $usuarios->registrar_usuario($nombre,$apellido,$cedula,$telefono,
                                        $email,$direccion,$cargo,$usuario,$pass1,$pass2,$estado);
                                        $messages[]="El usuario se registró correctamente";
                                    }else{//mostrar el mensaje que la cedula y correo ya existe
                                        $messages[]="La cedula y el correo ya existen";
                                    }
                                }else{//relizar la edicion de los datos del usuario
                                    $usuarios->editar_usuario($id,$nombre,$apellido,$cedula,$telefono,
                                        $email,$direccion,$cargo,$usuario,$pass1,$pass2,$estado);
                                        $messages[]="El usuario se actualizo correctamente";
                                }

                            }else{//mostrar el error de contraseña inconsistente
                                $errores[]="El password no coincide";
                            }//Cierre de IF ELSE

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
                            break;
    case "mostrar":break;
    case "activarydesactivar":break;
    case "listar":break;
    
}

?>