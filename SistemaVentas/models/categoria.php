<?php
/**
 * Archivo que contiene la clase Categoria
 * con las funciones de crear,editar,borrar
 * consultar registros en la base de datos
 */

  require_once("../config/conexion.php");

   class Categoria extends Conectar{

       //método para seleccionar todos los registros
   	   public function get_categorias(){
   	   	  $conectar=parent::conexion();
   	   	  parent::set_names();

   	   	  $sql="select * from categoria";

   	   	  $sql=$conectar->prepare($sql);
   	   	  $sql->execute();

   	   	  return $sql->fetchAll(PDO::FETCH_ASSOC);
   	   }

   	    //método para mostrar los datos de un registro a modificar
        public function get_categoria_id($id_categoria){
            $conectar= parent::conexion();
            parent::set_names();

            $sql="select * from categoria where id_categoria=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $id_categoria);
            $sql->execute();

            return $sql->fetchAll();
        }

		//método para mostrar los datos de un registro por usuario
        public function get_categoria_idusuario($id_usuario){
            $conectar= parent::conexion();
            parent::set_names();

            $sql="select * from categoria where id_usuario=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $id_usuario);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }


        //método para insertar registros
        public function registrar_categoria($categoria,$estado,$id_usuario){
           $conectar= parent::conexion();
           parent::set_names();

           $sql="insert into categoria values(null,?,?,?);";

           $sql=$conectar->prepare($sql);
           $sql->bindValue(1,$_POST["categoria"]);
           $sql->bindValue(2,$_POST["estado"]);
           $sql->bindValue(3,$_POST["id_usuario"]);
           $sql->execute();
      
        }
        
        //método para aeditar un registro de la tabla categoria
        public function editar_categoria($id_categoria,$categoria,$estado,$id_usuario){
        	$conectar=parent::conexion();
        	parent::set_names();

        	$sql="update categoria set categoria=?,estado=?,id_usuario=? where id_categoria=? ";
            
           //echo $sql; exit(); Forma de identificar un error en la consulta

        	  $sql=$conectar->prepare($sql);
		    $sql->bindValue(1,$_POST["categoria"]);
		    $sql->bindValue(2,$_POST["estado"]);
		    $sql->bindValue(3,$_POST["id_usuario"]);
		    $sql->bindValue(4,$_POST["id_categoria"]);
		    $sql->execute();
 
               //impriendo el envio de los datos
               //print_r($nombre); Forma de identificar un error en la ejecucion del registro

        }

         //método para activar Y/0 desactivar el estado de la categoria
        public function editar_estado($id_categoria,$estado){
        	 $conectar=parent::conexion();
             parent::set_names();

        	 //si el estado es igual a 0 entonces el estado cambia a 1
        	 //el parametro est se envia por via ajax
        	 if($_POST["est"]=="0"){
        	   $estado=1;
        	 } else {
        	 	 $estado=0;
        	 }

        	 $sql="update categoria set  estado=? where id_categoria=? ";

        	 $sql=$conectar->prepare($sql);

        	 $sql->bindValue(1,$estado);
        	 $sql->bindValue(2,$id_categoria);
        	 $sql->execute();
        }


        //método si la categoria existe en la base de datos
        public function get_nombre_categoria($categoria){
           $conectar=parent::conexion();
           parent::set_names();

          $sql="select * from categoria where categoria=?";

           //echo $sql; exit();

           $sql=$conectar->prepare($sql);

           $sql->bindValue(1,$categoria);
           $sql->execute();

           //print_r($email); exit();

           return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        //método para eliminar un registro
        public function eliminar_categoria($id_categoria){
         $conectar=parent::conexion();
         parent::set_names();

         $sql="delete from categoria where id_categoria=?";
         $sql=$conectar->prepare($sql);
         $sql->bindValue(1,$id_categoria);
         $sql->execute();
         
         return $sql->fetch();
      }
	  
	  //consulta si el id_categoria tiene una compra asociada
      public function get_categoria_compras($id_categoria){
			$conectar=parent::conexion();
            parent::set_names();
			
			$sql="select c.id_categoria,comp.id_categoria from categoria c 
              INNER JOIN compras comp ON c.id_categoria=comp.id_categoria
			where c.id_categoria=? ";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$id_categoria);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);

		}

      
      //consulta si el id_categoria tiene un detalle_compra asociado
      public function get_categoria_detallecompras($id_categoria){
            $conectar=parent::conexion();
            parent::set_names();

           $sql="select c.id_categoria,d.id_categoria from categoria c             
              INNER JOIN detalle_compras d ON c.id_categoria=d.id_categoria
              where c.id_categoria=? ";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$id_categoria);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);       
      }

   }

?>