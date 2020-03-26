<?php
/**
 * Archivo que contiene los procesos de consultas
 * de la informacion tabla Proveedor por medio de ajax
 */

//importar la conexion de la BD y el modelo proveedor
require_once("../config/conexion.php");
require_once("../models/proveedor.php");

$proveedores = new Proveedor();

/** declaramos las variables de los valores que se 
 * envian por el formulario y que recibimos por ajax
 * decimos que si existe el parametro que estamos recibiendo
 * los valores vienen del atributo name de los campos del formulario
 * el valor id_usuario y cedula_proveedor se carga en 
 * el campo hidde cuando se edita un registro
 * se copian los campos de la tabla proveedor
*/

$id_usuario=isset($_POST["id_usuario"]);
$cedula_proveedor=isset($_POST["cedula_proveedor"]);
$cedula=isset($_POST["cedula"]);
$telefono=isset($_POST["telefono"]);
$email=isset($_POST["email"]);
$proveedor=isset($_POST["razon"]);
$direccion=isset($_POST["direccion"]);
$estado=isset($_POST["estado"]);

switch($_GET["op"]){
                            /**verificamos si existe la cedula y correo en la BD,
                             * si ya existe un registro con la categoria entonces no 
                             * se registra el proveedor**/
    case "guardaryeditar":$datos = $proveedores->get_datos_proveedor($_POST["cedula"],$_POST["razon"],$_POST["email"]);
                           /**si la cedula_proveedor no existe entonces lo registra 
                            *importante: se debe poner el $_POST sino no funciona  **/
                            if(empty($_POST["cedula_proveedor"])){
                                /**verificamos si la cedula del proveedor en la base de datos,
                                     * si ya existe un registro con el proveedor entonces no se registra  **/
                                if(is_array($datos)==true and count($datos)==0){
                                    //no existe el proveedor por lo tanto hacemos el registro
                                    $proveedores->registrar_proveedor($cedula,$proveedor,$telefono,
                                                                        $email,$direccion,$estado,$id_usuario);
                                        $messages[]="El proveedor se registró correctamente";
                                } //cierre de validacion de $datos 
                                else {/*si ya existes el proveedor entonces aparece el mensaje*/
                                    $errors[]="El Proveedor ya existe";
                                }
                            }//cierre de empty
                            else{//si ya existe entonces editamos el proveedor
                                $proveedores->editar_proveedor($cedula,$proveedor,$telefono,
                                                            $email,$direccion,$estado,$id_usuario);
                                $messages[]="El proveedor se actualizo correctamente";
                            }

                            require_once("../views/view_mensajes.php");
                            require_once("../views/view_alertas.php");

                            /**Mostrar los mesajes informativos de los procesos
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
                            }//Cierre del IF errors**/
                            break;
    case "mostrar"://Mostrar los datos del proveedor recuperados con el id enviado por ajax
                    $datos = $proveedores->get_proveedor_cedula($_POST["cedula_proveedor"]);
                    //validacion del id del proveedor
                    if(is_array($datos)==true and count($datos)>0){
                        //recuperacion de los datos del proveedor
                        foreach($datos as $row){
                            $output["cedula_proveedor"] = $row["cedula"];
						    $output["proveedor"] = $row["razon_social"];
                            $output["telefono"] = $row["telefono"];
                            $output["correo"] = $row["correo"];
                            $output["direccion"] = $row["direccion"];
                            $output["fecha"] = $row["fecha"];
                            $output["estado"] = $row["estado"];
                        }
                        echo json_encode($output); //mostrar los datos

                    }else{//Mostrar el mensaje de error que el proveedor no existe
                        $errores[]="El proveedor no existe";
                    }

                    require_once("../views/view_alertas.php");

                    /**Mostrar los mensajes de errores de las operaciones
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
                    }//Cierre del IF errors**/
                    break; //Termino del proceso mostrar datos por id
    case "activarydesactivar":
                            $datos = $proveedores->get_proveedor_por_id($_POST["id_proveedor"]);
                            //valida si existe el proveedor
                            if(is_array($datos)==true and count($datos>0)){
                                $proveedores->editar_estado($_POST["id_proveedor"],$_POST["est"]);
                                //edita el estado del proveedor
                            }
                            break; //Termino del proceso activar y desactivar estado
    case "listar":
                    $datos = $proveedores->get_proveedores(); //Recuperamos la lista de los proveedor
                    $data = Array(); //Declaramos el arreglo
                    foreach($datos as $row){
                        $sub_array = array();
                        $est = '';
				        $atrib = "btn btn-success btn-md estado";
				        if($row["estado"] == 0){
					        $est = 'INACTIVO';
					        $atrib = "btn btn-warning btn-md estado";
				        }else{
					        if($row["estado"] == 1){
						        $est = 'ACTIVO';
					        }	
				        }
				
	                    $sub_array[] = $row["cedula"];
				        $sub_array[] = $row["razon_social"];
				        $sub_array[] = $row["telefono"];
				        $sub_array[] = $row["correo"];
				        $sub_array[] = $row["direccion"];
				        $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
                        //Botones cambiar estado, mostrar y editar perfil
                        $sub_array[] = '<button type="button" onClick="cambiarEstado('.$row["id_proveedor"].','.$row["estado"].');" name="estado" id="'.$row["id_proveedor"].'" class="'.$atrib.'">'.$est.'</button>';
                        $sub_array[] = '<button type="button"  onClick="mostrar('.$row["cedula"].');" id="'.$row["id_proveedor"].'" class="btn btn-warning btn-md"><i class="glyphicon glyphicon-edit"></i> Editar</button>';
                        $sub_array[] = '<button type="button" onClick="eliminar('.$row["id_proveedor"].');" id="'.$row["id_proveedor"].'" class="btn btn-danger btn-md"><i class="glyphicon glyphicon-edit"></i> Eliminar</button>';
                
				        $data[] = $sub_array;
                    }

                    $results = array(
                                "sEcho"=>1, //Informacion para el datatables
                                "iTotalRecords"=>count($data), //enviamos el total de registros al datatable
                                "iTotalDisplayRecords"=>count($data), //Enviamos el total registros a visualizar
                                "aaData"=>$data);
                    echo json_encode($results);
                    break;
    
    case "listar_en_compras":/*se muestran en ventana modal el datatable de los proveedores en compras para seleccionar 
                            luego los proveedores activos y luego se autocomplementa los campos desde un formulario*/
                            $datos=$proveedores->get_proveedores();
                    
                            //Vamos a declarar un array
                            $data= Array();
                    
                            foreach($datos as $row){
                                $sub_array = array();
                                $est = '';
                                $atrib = "btn btn-success btn-md estado";
                                if($row["estado"] == 0){
                                    $est = 'INACTIVO';
                                    $atrib = "btn btn-warning btn-md estado";
                                } else{
                                    if($row["estado"] == 1){
                                        $est = 'ACTIVO';
                                    }	
                                }
                                    
                                //$sub_array = array();
                                $sub_array[] = $row["cedula"];
                                $sub_array[] = $row["razon_social"];
                                $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
                                        
                                //Botones estado y agregar registro
                                $sub_array[] = '<button type="button"  name="estado" id="'.$row["id_proveedor"].'" class="'.$atrib.'">'.$est.'</button>';
                                $sub_array[] = '<button type="button" onClick="agregar_registro('.$row["id_proveedor"].','.$row["estado"].');" id="'.$row["id_proveedor"].'" class="btn btn-primary btn-md"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</button>';
                                    
                                $data[] = $sub_array;
                            }
                    
                            $results = array(
                                    "sEcho"=>1, //Información para el datatables
                                    "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                                    "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                                    "aaData"=>$data);
                                echo json_encode($results);
                    
                            break;
   
   
         /*valida los proveedores activos y se muestran en un formulario*/
        case "buscar_proveedor";
                                $datos=$proveedores->get_proveedor_por_id_estado($_POST["id_proveedor"],$_POST["est"]);

                                // comprobamos que el proveedor esté activo, de lo contrario no lo agrega
                                if(is_array($datos)==true and count($datos)>0){
                                    foreach($datos as $row){
                                        $output["cedula"] = $row["cedula"];
                                        $output["razon_social"] = $row["razon_social"];
                                        $output["direccion"] = $row["direccion"];
                                        $output["fecha"] = $row["fecha"];
                                        $output["estado"] = $row["estado"];  
                                    }
                                } else {//si no existe el registro entonces no recorre el array
                                    $output["error"]="El proveedor seleccionado está inactivo, intenta con otro";
                                }
                                echo json_encode($output);
                    
                            break;
    
}

?>