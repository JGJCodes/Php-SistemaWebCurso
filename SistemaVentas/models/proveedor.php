<?php
/**
 * Archivo que contiene la clase Proveedor
 * con las funciones de crear,editar,borrar
 * consultar registros en la base de datos
 */
 
 require_once("../config/conexion.php");//conexión a la base de datos

 class Proveedor extends Conectar{

    //método para retornar una lista de todos los registros
    public function get_proveedores(){
        $conectar= parent::conexion();
        parent::set_names();
     
        $sql= "select * from proveedor";

        $sql=$conectar->prepare($sql);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC); 
    }
	
	//retorna el numero total de registros en la tabla proveedor
	  public function get_filas_proveedor(){
			$conectar= parent::conexion();        
			$sql="select * from proveedor";         
			$sql=$conectar->prepare($sql);
			$sql->execute();
			$resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
			return $sql->rowCount();
      }

    //método para insertar registros
    public function registrar_proveedor($cedula,$razon,$telefono,
                                    $email,$direccion,$estado,$id_usuario){
        $conectar=parent::conexion();
        parent::set_names();

        $sql="insert into proveedor values(null,?,?,?,?,?,now(),?,?);";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $_POST["cedula"]);
        $sql->bindValue(2, $_POST["razon"]);
        $sql->bindValue(3, $_POST["telefono"]);
        $sql->bindValue(4, $_POST["email"]);
        $sql->bindValue(5, $_POST["direccion"]);
        $sql->bindValue(6, $_POST["estado"]);
        $sql->bindValue(7, $_POST["id_usuario"]);
        $sql->execute();
    }

    /**obtiene el registro por id despues de editar
     * este metodo es para validar el id del proveedor
     * luego llamamos el metodo de editar_estado()
     * el id_proveedor se envia por ajax cuando se 
     * edita el boton cambiar estado y que se ejecuta el 
     * evento onclick y llama la funcion javascript**/
    public function get_proveedor_id($id_proveedor){
        $conectar= parent::conexion();
        parent::set_names();

        //$output = array();

        $sql="select * from proveedor where id_proveedor=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $id_proveedor);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

	//método para mostrar los datos de un registro por usuario
    public function get_proveedor_idusuario($id_usuario){
            $conectar= parent::conexion();
            parent::set_names();

            $sql="select * from proveedor where id_usuario=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $id_usuario);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //metodo para mostrar los datos de un registro a modificar
    public function get_proveedor_cedula($cedula){
        $conectar= parent::conexion();
        parent::set_names();

        $sql="select * from proveedor where cedula=?";
        
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $cedula);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //metodo que valida si hay registros activos
    public function get_proveedor_estado($id_proveedor,$estado){
        $conectar= parent::conexion();
        parent::set_names();

        $estado = 1; //declaramos que el estado este activo, igual a 1

        $sql="select * from proveedor where id_proveedor=? and estado=?";
        
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $id_proveedor);
        $sql->bindValue(2, $estado);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**metodo si el proveedor existe en la base de datos 
    valida si existe la cedula, proveedor o correo, si existe
    entonces se hace el registro del proveedor
    **/
    public function get_datos_proveedor($cedula,$proveedor,$correo){
        $conectar= parent::conexion();
        parent::set_names();

        $sql="select * from proveedor where cedula=? or 
                razon_social=? or correo=?";
        
       //echo $sql;exit();
                 
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $cedula);
        $sql->bindValue(2, $proveedor);
        $sql->bindValue(3, $correo);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
        
        //print_r($email);exit();
    }

    //método para editar registro
    public function editar_proveedor($cedula,$razon,$telefono,
                                    $email,$direccion,$estado,$id_usuario){
        $conectar=parent::conexion();
        parent::set_names();

		require_once("proveedor.php");

        $proveedor = new Proveedor();

        //verifica si la cedula tiene registro asociado a compras
        $proveedor_compras=$proveedor->get_proveedor_cedula_compras($_POST["cedula_proveedor"]);

        //verifica si la cedula tiene registro asociado a detalle_compras
        $proveedor_detalle_compras=$proveedor->get_proveedor_cedula_detalle_compras($_POST["cedula_proveedor"]);

        /*si la cedula del proveedor NO tiene registros asociados en las tablas compras 
		y detalle_compras entonces se puede editar el proveedor completo*/
        if(is_array($proveedor_compras)==true and count($proveedor_compras)==0 and 
			is_array($proveedor_detalle_compras)==true and count($proveedor_detalle_compras)==0){

			$sql="update proveedor set cedula=?,razon_social=?,telefono=?,
				correo=?,direccion=?,estado=?,id_usuario=? where cedula=?";

			//echo $sql;exit();

			$sql=$conectar->prepare($sql);
			$sql->bindValue(1, $_POST["cedula"]);
			$sql->bindValue(2, $_POST["razon"]);
			$sql->bindValue(3, $_POST["telefono"]);
			$sql->bindValue(4, $_POST["email"]);
			$sql->bindValue(5, $_POST["direccion"]);
			$sql->bindValue(6, $_POST["estado"]);
			$sql->bindValue(7, $_POST["id_usuario"]);
			$sql->bindValue(8, $_POST["cedula_proveedor"]);
			$sql->execute();
			
		} else {       
          /*si el proveedor tiene registros asociados en compras y detalle_compras entonces
		  no se edita el la cedula del proveedor y la razon social*/

            $sql="update proveedor set telefono=?, correo=?,direccion=?,  
                  estado=?, id_usuario=? where cedula=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $_POST["telefono"]);
            $sql->bindValue(2, $_POST["email"]);
            $sql->bindValue(3, $_POST["direccion"]);
            $sql->bindValue(4, $_POST["estado"]);
            $sql->bindValue(5, $_POST["id_usuario"]);
            $sql->bindValue(6, $_POST["cedula_proveedor"]);
            $sql->execute();
        }
    }
    
    //método para activar Y/0 desactivar el estado del producto
    public function editar_estado($id_proveedor,$estado){
        $conectar=parent::conexion();
        parent::set_names();
                  
        /**si estado es igual a 0 entonces lo cambia a 1
        //el parametro est viene por via ajax, viene de est:est**/
        $estado = 0;
        if($_POST["est"] == 0){
            $estado = 1;
        }

        $sql="update proveedor set estado=? where id_proveedor=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $estado);
        $sql->bindValue(2, $id_proveedor);
        $sql->execute();
    }

    //método para eliminar un proveedor
    public function eliminar_proveedor($id_proveedor){
        $conectar=parent::conexion();
        parent::set_names();

        $sql="delete from proveedor where id_proveedor=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $id_proveedor);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }
	
	//consulta si la cedula del proveedor tiene una compra asociada
    public function get_proveedor_cedula_compras($cedula_proveedor){
        $conectar=parent::conexion();
        parent::set_names();

        $sql="select p.cedula,c.cedula_proveedor from proveedor p             
              INNER JOIN compras c ON p.cedula=c.cedula_proveedor
              where p.cedula=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1,$cedula_proveedor);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);

    }

      
    //consulta si la cedula del proveedor tiene un detalle_compra asociado
    public function get_proveedor_cedula_detalle_compras($cedula_proveedor){
        $conectar=parent::conexion();
        parent::set_names();

        $sql="select p.cedula,d.cedula_proveedor from producto p        
              INNER JOIN detalle_compras c ON p.cedula=d.cedula_proveedor
              where p.cedula=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1,$cedula_proveedor);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

 }
?>
