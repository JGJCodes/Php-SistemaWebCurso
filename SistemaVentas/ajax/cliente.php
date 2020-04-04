<?php
/**
 * Archivo que contiene los procesos de consultas
 * de la informacion tabla Cliente por medio de ajax
 */

  //llamo a la conexion de la base de datos 
  require_once("../config/conexion.php");
  //llamo al modelo Clientes
   require_once("../models/cliente.php");


  $clientes = new Cliente();


  //declaramos las variables de los valores que se envian por el formulario y que recibimos por ajax y decimos que si existe el parametro que estamos recibiendo

//los valores vienen del atributo name de los campos del formulario
/*el valor id_usuario y cedula_cliente se carga en el campo hidden cuando se edita un registro*/
//se copian los campos de la tabla clientes
$id_usuario=isset($_POST["id_usuario"]);
$cedula_cliente=isset($_POST["cedula_cliente"]);
$cedula = isset($_POST["cedula"]);
$nombre=isset($_POST["nombre"]);
$apellido=isset($_POST["apellido"]);
$telefono=isset($_POST["telefono"]);
$correo=isset($_POST["email"]);
$direccion=isset($_POST["direccion"]);
$estado=isset($_POST["estado"]);


   switch($_GET["op"]){

        case "guardaryeditar":

   /*verificamos si existe el cliente en la base de datos*/
   
   //importante: se debe poner el $_POST sino no funciona
   $datos = $clientes->get_datos_cliente($_POST["cedula"],$_POST["nombre"],$_POST["email"]);

 
               /*si la cedula_cliente no existe entonces lo registra
            importante: se debe poner el $_POST sino no funciona*/
           if(empty($_POST["cedula_cliente"])){

              /*verificamos si la cedula del cliente en la base de datos, si ya existe un registro con el cliente entonces no se registra*/

                       if(is_array($datos)==true and count($datos)==0){

                             //no existe el cliente por lo tanto hacemos el registros

      $clientes->registrar_cliente($cedula,$nombre,$apellido,$telefono,$correo,$direccion,$estado,$id_usuario);



                             $messages[]="El Cliente se registró correctamente";

                       } //cierre de validacion de $datos 


                          /*si ya existes el cliente entonces aparece el mensaje*/
                           else {

                                 $errors[]="El Cliente ya existe";
                           }

             }//cierre de empty

             else {


                 /*si ya existe entonces editamos el proveedor*/


              $clientes->editar_cliente($cedula,$nombre,$apellido,$telefono,$correo,$direccion,$estado,$id_usuario);


                   $messages[]="El cliente se editó correctamente";

                  
             }

 
   
  //mensaje success
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

         }

  //fin mensaje error


  break;

  case 'mostrar':

 
 //el parametro cedula se envia por AJAX cuando se edita el cliente
 $datos=$clientes->get_cliente_por_cedula($_POST["cedula_cliente"]);


       // si existe el id del cliente entonces recorre el array
       if(is_array($datos)==true and count($datos)>0){


                 foreach($datos as $row)
                 {
                     $output["cedula_cliente"] = $row["cedula_cliente"];
                     $output["nombre"] = $row["nombre_cliente"];
                     $output["apellido"] = $row["apellido_cliente"];
                     $output["telefono"] = $row["telefono_cliente"];
                     $output["correo"] = $row["correo_cliente"];
                     $output["direccion"] = $row["direccion_cliente"];
                     $output["fecha"] = $row["fecha_ingreso"];
                     $output["estado"] = $row["estado"];

                 }


               echo json_encode($output);


         } else {
              
              //si no existe el cliente entonces no recorre el array
             $errors[]="El cliente no existe";

         }


          //inicio de mensaje de error

             if(isset($errors)){
         
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

         //fin de mensaje de error


  break;

   case "activarydesactivar":
  
  //los parametros id_cliente y est vienen por via ajax
  $datos=$clientes->get_cliente_por_id($_POST["id_cliente"]);

       // si existe el id del cliente entonces recorre el array
       if(is_array($datos)==true and count($datos)>0){

           //edita el estado del cliente
           $clientes->editar_estado($_POST["id_cliente"],$_POST["est"]);
          
         } 

  break;

  case "listar":

  $datos=$clientes->get_clientes();

  //Vamos a declarar un array
   $data= Array();

  foreach($datos as $row)

         {
             $sub_array = array();

             $est = '';
             
              $atrib = "btn btn-success btn-md estado";
             if($row["estado"] == 0){
                 $est = 'INACTIVO';
                 $atrib = "btn btn-warning btn-md estado";
             }
             else{
                 if($row["estado"] == 1){
                     $est = 'ACTIVO';
                     
                 }	
             }
             
         
              $sub_array[] = $row["cedula_cliente"];
              $sub_array[] = $row["nombre_cliente"];
              $sub_array[] = $row["apellido_cliente"];
              $sub_array[] = $row["telefono_cliente"];
              $sub_array[] = $row["correo_cliente"];
              $sub_array[] = $row["direccion_cliente"];
              $sub_array[] = date("d-m-Y",strtotime($row["fecha_ingreso"]));
             

              $sub_array[] = '<button type="button" onClick="cambiarEstado('.$row["id_cliente"].','.$row["estado"].');" name="estado" id="'.$row["id_cliente"].'" class="'.$atrib.'">'.$est.'</button>';
             
             

              $sub_array[] = '<button type="button" onClick="mostrar('.$row["cedula_cliente"].');" id="'.$row["id_cliente"].'" class="btn btn-warning btn-md"><i class="glyphicon glyphicon-edit"></i> Editar</button>';
              

              $sub_array[] = '<button type="button" onClick="eliminar('.$row["id_cliente"].');" id="'.$row["id_cliente"].'" class="btn btn-danger btn-md"><i class="glyphicon glyphicon-edit"></i> Eliminar</button>';
             
             $data[] = $sub_array;
         }

   $results = array(
          "sEcho"=>1, //Información para el datatables
          "iTotalRecords"=>count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
          "aaData"=>$data);
      echo json_encode($results);


  break;

   /*se muestran en ventana modal el datatable de los clientes en ventas para seleccionar luego los clientes activos y luego se autocomplementa los campos de un formulario*/
  case "listar_en_ventas":

  $datos=$clientes->get_clientes();

  //Vamos a declarar un array
   $data= Array();

  foreach($datos as $row)
         {
             $sub_array = array();

             $est = '';
             
              $atrib = "btn btn-success btn-md estado";
             if($row["estado"] == 0){
                 $est = 'INACTIVO';
                 $atrib = "btn btn-warning btn-md estado";
             }
             else{
                 if($row["estado"] == 1){
                     $est = 'ACTIVO';
                     
                 }	
             }
             
             
              $sub_array[] = $row["cedula_cliente"];
              $sub_array[] = $row["nombre_cliente"];
              $sub_array[] = $row["apellido_cliente"];
              
             
              $sub_array[] = '<button type="button"  name="estado" id="'.$row["id_cliente"].'" class="'.$atrib.'">'.$est.'</button>';


              $sub_array[] = '<button type="button" onClick="agregar_registro('.$row["id_cliente"].','.$row["estado"].');" id="'.$row["id_cliente"].'" class="btn btn-primary btn-md"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</button>';
             
             $data[] = $sub_array;
         }

   $results = array(
          "sEcho"=>1, //Información para el datatables
          "iTotalRecords"=>count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
          "aaData"=>$data);
      echo json_encode($results);


  break;


  /*valida los clientes activos y se muestran en un formulario*/
  case "buscar_cliente":


 $datos=$clientes->get_cliente_por_id_estado($_POST["id_cliente"],$_POST["est"]);


       // comprobamos que el cliente esté activo, de lo contrario no lo agrega
       if(is_array($datos)==true and count($datos)>0){

             foreach($datos as $row)
             {
                 $output["cedula_cliente"] = $row["cedula_cliente"];
                 $output["nombre"] = $row["nombre_cliente"];
                 $output["apellido"] = $row["apellido_cliente"];
                 $output["direccion"] = $row["direccion_cliente"];
                 $output["estado"] = $row["estado"];
                 
             }

         

         } else {
              
              //si no existe el registro entonces no recorre el array
             
              $output["error"]="El cliente seleccionado está inactivo, intenta con otro";


         }

         echo json_encode($output);

  break;

  

      
  }



?>