<?php
/**
 * Archivo que contiene los procesos de consultas
 * de la informacion tabla Empresa por medio de ajax
 */

require_once("../config/conexion.php");//llamo a la conexion de la base de datos  
require_once("../models/empresa.php");//llamo al modelo Empresas

$empresa = new Empresas();

/*declaramos las variables de los valores que se envian por el 
    formulario y que recibimos por ajax y decimos que si 
    existe el parametro que estamos recibiendo
    *los valores vienen del atributo name de los campos del formulario
*/
$id_usuario=isset($_POST["id_usuario_empresa"]);
$nombre=isset($_POST["nombre_empresa"]);
$cedula=isset($_POST["cedula_empresa"]);
$telefono=isset($_POST["telefono_empresa"]);
$email=isset($_POST["email_empresa"]);
$direccion=isset($_POST["direccion_empresa"]);

switch($_GET["op"]){

  case "empresa":
            //selecciona el id del usuario
            $datos = $empresa->get_empresa_usuario($_POST["id_usuario_empresa"]);

            // si existe el id_usuario_empresa entonces recorre el array
            if(is_array($datos)==true and count($datos)==0){
                foreach($datos as $row){
					$output["cedula"] = $row["cedula_empresa"];
					$output["nombre"] = $row["nombre_empresa"];
					$output["telefono"] = $row["telefono_empresa"];
					$output["correo"] = $row["correo_empresa"];
					$output["direccion"] = $row["direccion_empresa"];
				}
	        } 
	        echo json_encode($output);
  break;
       
  case "editar_empresa":  	 
        /*verificamos si la empresa existe en la base de datos, 
        si ya existe un registro con la cedula, 
        nombre o correo entonces se edita la empresa*/
        $datos= $empresa->get_datos_empresa($_POST["cedula_empresa"],
                                $_POST["nombre_empresa"],$_POST["email_empresa"]);
  
        if(is_array($datos)==true and count($datos)>0 ){
            //si ya existe entonces editamos la empresa
               $empresa->editar_empresa($_POST["id_usuario_empresa"],$_POST["nombre_empresa"],
                                    $_POST["cedula_empresa"],$_POST["telefono_empresa"],
                                    $_POST["email_empresa"],$_POST["direccion_empresa"]);
            $messages[]="La empresa se editó correctamente";
        }//cierre condicional $datos
        else{ 
            $errors[]="La empresa no existe";        
        }
  
        require_once("../views/view_mensajes.php");
        require_once("../views/view_alertas.php");
  
    /*prueba mensaje de success
  
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
  
  
        //fin mensaje success
  
  
         //inicio de mensaje de error
  
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
  
    //fin de mensaje de error*/
  break;
   
}//Cierre del switch

?>