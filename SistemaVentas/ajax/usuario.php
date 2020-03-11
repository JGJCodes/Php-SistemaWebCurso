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
    case "mostrar"://Mostrar los datos del usuario recuperados con el id enviado por ajax
                    $datos = $usuarios->get_usuarioid($_POST["id_usuario"]);
                    //validacion del id del usuario
                    if(is_array($datos)==true and count($datos)>0){
                        //recuperacion de los datos del usuario
                        foreach($datos as $row){
                            $output["cedula"]=$row["cedula"];
                            $output["nombre"]=$row["nombres"];
                            $output["apellido"]=$row["apellidos"];
                            $output["cargo"]=$row["cargo"];
                            $output["usuario"]=$row["usuario"];
                            $output["password1"]=$row["password"];
                            $output["password2"]=$row["password2"];
                            $output["telefono"]=$row["telefono"];
                            $output["correo"]=$row["correo"];
                            $output["direccion"]=$row["direccion"];
                            $output["estado"]=$row["estado"];
                        }
                        echo json_encode($output); //mostrar los datos

                    }else{//Mostrar el mensaje de error que el usuario no existe
                        $errores[]="El usuario no existe";
                    }
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
                    break; //Termino del proceso mostrar datos por id
    case "activarydesactivar":
                            $datos = $usuarios->get_usuarioid($_POST["id_usuario"]);
                            //valida si existe el usuario
                            if(is_array($datos)==true and count($datos>0)){
                                $usuarios->editar_estado($_POST["id_usuario"],$_POST["estado"]);
                                //edita el estado del usuario
                            }
                            break; //Termino del proceso activar y desactivar estado
    case "listar":
                    $datos = $usuarios->get_usuarios(); //Recuperamos la lista de los usuarios
                    $data = Array(); //Declaramos el arreglo
                    foreach($datos as $row){
                        $sub_array=array();

                        //Verifica el estado del usuario
                        $estado = '';
                        $atrib ="btn btn-success btn-md estado";
                        if($row["estado"]==0){
                            $estado = 'INACTIVO';
                            $atrib = "btn btn-warning btn-md estado";
                        }else{
                            if($row["estado"]==1){
                                $estado = 'ACTIVO';
                            }
                        }

                        //Evalua el puesto del usuario
                        if($row["cargo"]==1){
                            $cargo = "ADMINISTRADOR";
                        }else{
                            if($row["cargo"]==0){
                                $cargo = "EMPLEADO";
                            }
                        }
                        //asigna los datos a un arreglo
                        $sub_array[]=$row["cedula"];
                        $sub_array[]=$row["nombres"];
                        $sub_array[]=$row["apellidos"];
                        $sub_array[]=$row["usuario"];
                        $sub_array[]=$row["telefono"];
                        $sub_array[]=$row["correo"];
                        $sub_array[]=$row["direccion"];
                        $sub_array[]=date("d-m-Y",strtotime($row["fecha_ingreso"]));
                        $sub_array[]=$row["cedula"];
                        //boton  estado del usuario
                        $sub_array[]='<button type="button" onClick="cambiarEstado('.$row["id_usuario"].','.
                                        $row["estado"].');" name="estado" id="'.$row["id_usuario"].'" class="'.
                                        $atrib.'">'.$estado.'</button>';
                        //boton editar estado del usuario
                        $sub_array[]='<button type="button" onClick="mostrar('.$row["id_usuario"].');" id="'.
                                        $row["id_usuario"].'" class="btn btn-warning btn-md update">
                                        <i class="glyphicon glyphicon-edit"></i> Editar</button>';
                        //boton eliminar estado del usuario
                        $sub_array[]='<button type="button" onClick="eliminar('.$row["id_usuario"].');" id="'.
                                        $row["id_usuario"].'" class="btn btn-danger btn-md update">
                                        <i class="glyphicon glyphicon-edit"></i> Eliminar</button>';
                        $data[]=$sub_array;
                    }

                    $results = array(
                                "sEcho"=>1, //Informacion para el datatables
                                "iTotalRecords"=>count($data), //enviamos el total de registros al datatable
                                "iTotalDisplayRecords"=>count($data), //Enviamos el total registros a visualizar
                                "aaData"=>$data);
                    echo json_encode($results);
                    break;
    
}

?>