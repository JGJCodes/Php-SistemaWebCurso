<?php
/**
 * Archivo que contiene los procesos de consultas
 * de la informacion tabla Compras por medio de ajax
 */

  require_once("../config/conexion.php");//llamo a la conexion de la base de datos 
  require_once("../models/compra.php"); //llamo al modelo Categoría

  $compras = new Compras();

  switch($_GET["op"]){
          
          case "detalle_proveedor":
                    $datos= $compras->get_detalle_proveedor($_POST["numero_compra"]);	
         
                     // si existe el proveedor entonces recorre el array
                   if(is_array($datos)==true and count($datos)>0){
                         foreach($datos as $row){
                             $output["proveedor"] = $row["proveedor"];
                             $output["numero_compra"] = $row["numero_compra"];
                             $output["cedula_proveedor"] = $row["cedula_proveedor"];
                             $output["direccion"] = $row["direccion"];
                             $output["fecha_compra"] = date("d-m-Y", strtotime($row["fecha_compra"]));           
                         }
                        echo json_encode($output);
                     } else { 
                          //si no existe el registro entonces no recorre el array
                         $errors[]="no existe";
                     }
         
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
                            }//fin success
                    

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

    case "detalle_compra":
                $datos= $compras->get_detalle_compras_proveedor($_POST["numero_compra"]);	
	    break;

    case "buscar_compras":
            $datos=$compras->get_compras();
       
            //Vamos a declarar un array
             $data= Array();
       
            foreach($datos as $row){
                $sub_array = array();
                $est = '';
                $atrib = "btn btn-danger btn-md estado";

                if($row["estado"] == 1){
                    $est = 'PAGADO';
                    $atrib = "btn btn-success btn-md estado";
                } else {
                    if($row["estado"] == 0){
                        $est = 'ANULADO';
                        }	
                }//Cierre if else
       
                $sub_array[] = '<button class="btn btn-warning detalle"  id="'.
                                $row["numero_compra"].'"  data-toggle="modal" '.
                               ' data-target="#detalle_compra"><i class="fa fa-eye"></i></button>';
                $sub_array[] = date("d-m-Y", strtotime($row["fecha_compra"]));
                $sub_array[] = $row["numero_compra"];
                $sub_array[] = $row["proveedor"];
                $sub_array[] = $row["cedula_proveedor"];
                $sub_array[] = $row["comprador"];
                $sub_array[] = $row["tipo_pago"];
                $sub_array[] = $row["moneda"]." ".$row["total"];
        
            //IMPORTANTE: poner \' cuando no sea numero, sino no imprime*/
                $sub_array[] = '<button type="button" onClick="cambiarEstado('.
                                $row["id_compras"].',\''.$row["numero_compra"].
                                '\','.$row["estado"].');" name="estado" id="'.
                                $row["id_compras"].'" class="'.$atrib.'">'.
                                $est.'</button>';
                $data[] = $sub_array;
            }//Cierre del ciclo for
       
             $results = array(
                    "sEcho"=>1, //Información para el datatables
                    "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                    "aaData"=>$data);
                echo json_encode($results);
            break;
       
    case "cambiar_estado_compra":
                 $datos=$compras->get_compra_id($_POST["id_compras"]);
       
                 // si existe el id de la compra entonces se edita el estado del detalle de la compra
                 if(is_array($datos)==true and count($datos)>0){
                         //cambia el estado de la compra
                         $compras->cambiar_estado($_POST["id_compras"],
                          $_POST["numero_compra"], $_POST["est"]);
                   }
            break;
       
    case "buscar_compras_fecha":
            $datos=$compras->lista_busca_registros_fecha($_POST["fecha_inicial"], $_POST["fecha_final"]);
       
            //Vamos a declarar un array
             $data= Array();
       
            foreach($datos as $row) {
                       $sub_array = array();
                       $est = '';
                        $atrib = "btn btn-danger btn-md estado";

                       if($row["estado"] == 1){
                           $est = 'PAGADO';
                           $atrib = "btn btn-success btn-md estado";
                       }else{
                           if($row["estado"] == 0){
                               $est = 'ANULADO';    
                           }	
                       }//Cierre if else
           
                $sub_array[] = '<button class="btn btn-warning detalle" id="'.
                                $row["numero_compra"].'"  data-toggle="modal"'.
                                ' data-target="#detalle_compra"><i class="fa fa-eye"></i></button>';
                $sub_array[] = date("d-m-Y", strtotime($row["fecha_compra"]));
                $sub_array[] = $row["numero_compra"];
                $sub_array[] = $row["proveedor"];
                $sub_array[] = $row["cedula_proveedor"];
                $sub_array[] = $row["comprador"];
                $sub_array[] = $row["tipo_pago"];
                $sub_array[] = $row["moneda"]." ".$row["total"];
                       
                //IMPORTANTE: poner \' cuando no sea numero, sino no imprime*/
                $sub_array[] = '<button type="button" onClick="cambiarEstado('.$row["id_compras"].
                                ',\''.$row["numero_compra"].'\','.$row["estado"].');" name="estado" id="'.
                                $row["id_compras"].'" class="'.$atrib.'">'.$est.'</button>';        
                $data[] = $sub_array;
            }//Cierre ciclo for
       
             $results = array(
                    "sEcho"=>1, //Información para el datatables
                    "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                    "aaData"=>$data);
                echo json_encode($results);
         
            break;
       
       
    case "buscar_compras_fecha_mes": 
             $datos= $compras->lista_busca_registros_fecha_mes($_POST["mes"],$_POST["ano"]);
    
             $data= Array();//Vamos a declarar un array
       
            foreach($datos as $row) {
                       $sub_array = array();
                       $est = '';
                       //$atrib = 'activo';

                    $atrib = "btn btn-danger btn-md estado";
                       if($row["estado"] == 1){
                           $est = 'PAGADO';
                           $atrib = "btn btn-success btn-md estado";
                       } else {
                           if($row["estado"] == 0){
                               $est = 'ANULADO';
                               //$atrib = '';
                           }	
                       }//Cierre if else       
       
                    $sub_array[] = '<button class="btn btn-warning detalle" id="'.
                                    $row["numero_compra"].'"  data-toggle="modal"'.
                                ' data-target="#detalle_compra"><i class="fa fa-eye"></i></button>';
                    $sub_array[] = date("d-m-Y", strtotime($row["fecha_compra"]));
                    $sub_array[] = $row["numero_compra"];
                    $sub_array[] = $row["proveedor"];
                    $sub_array[] = $row["cedula_proveedor"];
                    $sub_array[] = $row["comprador"];
                    $sub_array[] = $row["tipo_pago"];
                    $sub_array[] = $row["moneda"]." ".$row["total"];
                       
                  //IMPORTANTE: poner \' cuando no sea numero, sino no imprime*/
                    $sub_array[] = '<button type="button" onClick="cambiarEstado('.
                                    $row["id_compras"].',\''.$row["numero_compra"].'\','.
                                    $row["estado"].');" name="estado" id="'.$row["id_compras"].
                                    '" class="'.$atrib.'">'.$est.'</button>';
                    $data[] = $sub_array;
            }//Cierre del ciclo for

             $results = array(
                    "sEcho"=>1, //Información para el datatables
                    "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                    "aaData"=>$data);
                echo json_encode($results);
       
        break;
       
}//Cierre switch


?>