<?php
/**
 * Archivo que contiene los procesos de consultas
 * de la informacion tabla Usuario por medio de ajax
 */

//importar la conexion de la BD y el modelo usuario
require_once("../config/conexion.php");
require_once("../models/usuario.php");

 /*llamo a los modelos categoria, cliente, compra, empresa, producto, 
 proveedor y venta para verificar si el usuario tiene registros 
 asociados a las tablas de la base de datos*/
 require_once("../models/categoria.php");
 require_once("../models/cliente.php");
 require_once("../models/compra.php");
 require_once("../models/empresa.php");
 require_once("../models/producto.php");
 require_once("../models/proveedore.php");
 require_once("../models/venta.php");

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
                           
    case "guardaryeditar":
					//verificamos si existe la cedula y correo en la BD
					
                            if($pass1==$pass2){
                                //verificamos si no existe el id del usuario
                                if(empty($_POST["id_usuario"])){
                                    //verificamos si se recuperaron los datos del usuario
									$datos = $usuarios->get_cedula_correo($_POST["cedula"],$_POST["email"]);
									
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
	
    case "mostrar":
			//selecciona el id del usuario
    
           //el parametro id_usuario se envia por AJAX cuando se edita el usuario
			$datos = $usuarios->get_usuario_id($_POST["id_usuario"]);

			//verifica si el id_usuario tiene registro asociado a compras
			$usuario_compras=$usuarios->get_usuario_idcompras($_POST["id_usuario"]);

			//verifica si el id_usuario tiene registro asociado a ventas
			$usuario_ventas=$usuarios->get_usuario_idventas($_POST["id_usuario"]);


			/*si el id_usuario NO tiene registros asociados en las tablas compras y 
			ventas entonces se puede editar todos los campos de la tabla usuarios*/
			if(is_array($usuario_compras)==true and count($usuario_compras)==0 and
				is_array($usuario_ventas)==true and count($usuario_ventas)==0){

             	foreach($datos as $row){                   
						$output["cedula"] = $row["cedula"];
						$output["nombre"] = $row["nombres"];
            			$output["apellido"] = $row["apellidos"];
            			$output["cargo"] = $row["cargo"];
            			$output["usuario"] = $row["usuario"];
            			$output["password1"] = $row["password"];
            			$output["password2"] = $row["password2"];
            			$output["telefono"] = $row["telefono"];
            			$output["correo"] = $row["correo"];
            			$output["direccion"] = $row["direccion"];
            			$output["estado"] = $row["estado"];       	 
				}        	
			} else {               
				/*si el id_usuario tiene relacion con la tabla compras y 
				tabla ventas entonces se deshabilita el nombre, apellido y cedula*/
                foreach($datos as $row){
                    $output["cedula_relacion"] = $row["cedula"];
                    $output["nombre"] = $row["nombres"];
                    $output["apellido"] = $row["apellidos"];
                    $output["cargo"] = $row["cargo"];
                    $output["usuario"] = $row["usuario"];
                    $output["password1"] = $row["password"];
                    $output["password2"] = $row["password2"];
                    $output["telefono"] = $row["telefono"];
                    $output["correo"] = $row["correo"];
                    $output["direccion"] = $row["direccion"];
                    $output["estado"] = $row["estado"];
                }
            }//cierre del else
          echo json_encode($output);
			
    break; //Termino del proceso mostrar datos por id
	
    case "activarydesactivar":
                            $datos = $usuarios->get_usuario_id($_POST["id_usuario"]);
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
	
	case "eliminar_usuario":
            /*verificamos si el usuario existe en las tablas productos, compras, clientes, compras,
			ventas, categoria, si existe entonces el usuario no se elimina, si no existe entonces 
			se puede eliminar el usuario*/
            $producto = new Producto();
            $categoria = new Categoria();
            $cliente = new Cliente();
            $compra =  new Compras();
            $empresa = new Empresa();
            $proveedor = new Proveedor();
            $venta = new Ventas();

			$prod= $producto->get_producto_idusuario($_POST["id_usuario"]);

			$cat= $categoria->get_categoria_idusuario($_POST["id_usuario"]);

			$cli= $cliente->get_cliente_idusuario($_POST["id_usuario"]);

			$comp= $compra->get_compras_idusuario($_POST["id_usuario"]);

			$detalle_comp= $compra->get_detalle_compras_idusuario($_POST["id_usuario"]);

			$emp= $empresa->get_empresa_idusuario($_POST["id_usuario"]);    
		
			$prov= $proveedor->get_proveedor_idusuario($_POST["id_usuario"]); 

			$vent= $venta->get_ventas_idusuario($_POST["id_usuario"]);

		   $detalle_vent= $venta->get_detalle_ventas_idusuario($_POST["id_usuario"]); 

		   $usuario_permiso= $usuarios->get_usuario_permiso_idusuario($_POST["id_usuario"]);


			if(is_array($usuario_permiso)==true and count($usuario_permiso)>0 or
			  is_array($prod)==true and count($prod)>0 or 
			  is_array($cat)==true and count($cat)>0 or 
			  is_array($cli)==true and count($cli)>0 or 
			  is_array($comp)==true and count($comp)>0 or 
			  is_array($detalle_comp)==true and count($detalle_comp)>0 or 
			  is_array($emp)==true and count($emp)>0 or 
			  is_array($prov)==true and count($prov)>0 or 
			  is_array($vent)==true and count($vent)>0 or 
			  is_array($detalle_vent)==true and count($detalle_vent)>0){

					//si existe el usuario en las tablas productos, compras, clientes, compras, ventas, categoria, no lo elimina
				  $errors[]="El usuario existe en los registros, no se puede eliminar";
        
			}//fin
			else{
			   $datos= $usuarios->get_usuario_por_id($_POST["id_usuario"]);

			//si el usuario no existe en las tablas de la bd y que existe en la tabla de usuario entonces se elimina
			   if(is_array($datos)==true and count($datos)>0){
					$usuarios->eliminar_usuario($_POST["id_usuario"]);
					$messages[]="El usuario se eliminó exitosamente";		  
			   }
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
    
}

?>