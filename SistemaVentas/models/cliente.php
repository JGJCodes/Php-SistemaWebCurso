<?php
/**
 * Archivo que define la clase Clientes
 * con las funciones de iniciar sesion,
 * registrar,borrar,actualizar,consultar
 * y editar los datos en la base de datos
 */

    require_once("../config/conexion.php");//requiere la conexion con la BD

    /** 
     * Clase que define a la tabla Clientes 
     * */
    class Clientes extends Conectar{
        /**
         * Metodo que enlista todos los registros 
         * de la tabla clientes de la BD
         */
        public function get_clientes(){
            $conectar=parent::conexion();//recoge la conexion con la BD
            parent::set_names();

            $sql = "select * from clientes";//define la sentencia sql de consulta

            $sql = $conectar->prepare($sql);//transforma la sentencia string en sql
            $sql->execute();//ejecuta la sentencia sql 

            return  $sql->fetchAll();//devuelve los resultados de la consulta
        }
		
		//retorna el numero total de registros en la tabla clientes
		public function get_filas_cliente(){
			$conectar= parent::conexion();        
			$sql="select * from clientes";         
			$sql=$conectar->prepare($sql);
			$sql->execute();
			$resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
			return $sql->rowCount();
		}

        /**
         * Metodo que registra un cliente 
         * en la tabla de la BD
         */
        public function registrar_cliente($cedula,$nombre,$apellido,$telefono,$email,$direccion,
                                         $estado,$id_usuario){
            $conectar=parent::conexion();
            parent::set_names();

            $sql="insert into clientes values(null,?,?,?,?,?,?,?,?,?,?,now(),?)";

            $sql=$conectar->prepare($sql);

            $sql->bindValue(1, $_POST["cedula"]);
            $sql->bindValue(2, $_POST["nombre"]);
            $sql->bindValue(3, $_POST["apellido"]);
            $sql->bindValue(4, $_POST["telefono"]);
            $sql->bindValue(5, $_POST["email"]);
            $sql->bindValue(6, $_POST["direccion"]);
            $sql->bindValue(7, $_POST["estado"]);
            $sql->bindValue(8, $_POST["id_usuario"]);
            $sql->execute();
        }

        /**
         * Metodo que actualiza un cliente 
         * en la tabla de la BD
         */
        public function editar_cliente($cedula,$nombre,$apellido,$telefono,$email,$direccion,
                                        $estado,$id_usuario){
            $conectar=parent::conexion();
            parent::set_names();

			require_once("cliente.php");

            $cliente = new Cliente();

            //verifica si la cedula tiene registro asociado a ventas
			$cliente_ventas=$cliente->get_cliente_cedula_ventas($_POST["cedula_cliente"]);

			//verifica si la cedula tiene registro asociado a detalle_ventas
			$cliente_detalle_ventas=$cliente->get_cliente_cedula_detalle_ventas($_POST["cedula_cliente"]);

            /*si la cedula del cliente NO tiene registros asociados en las tablas ventas y
			detalle_ventas entonces se puede editar el cliente completo*/
			if(is_array($cliente_ventas)==true and count($cliente_ventas)==0 and 
				is_array($cliente_detalle_ventas)==true and count($cliente_detalle_ventas)==0){

				$sql="update clientes set cedula_cliente=?, nombre_cliente=?, apellido_cliente=?, telefono_cliente=?,
						correo_cliente=?,direccion_cliente=?, estado=?, id_usuario=? where cedula_cliente=? ";

			   // echo $sql;  Imprime la sentencia sql en la consola

				$sql=$conectar->prepare($sql);
				$sql->bindValue(1, $_POST["cedula"]);
				$sql->bindValue(2, $_POST["nombre"]);
				$sql->bindValue(3, $_POST["apellido"]);
				$sql->bindValue(4, $_POST["telefono"]);
				$sql->bindValue(5, $_POST["email"]);
				$sql->bindValue(6, $_POST["direccion"]);
				$sql->bindValue(7, $_POST["estado"]);
				$sql->bindValue(8, $_POST["id_usuario"]);
				$sql->bindValue(9, $_POST["cedula_cliente"]);
				$sql->execute();
				
				//print_r($_POST); Imprime los resultados de la consulta en la pagina	
				
			} else{
				/*si el cliente tiene registros asociados en ventas y detalle_ventas 
				entonces NO se edita la cedula del cedula, nombre y apellido*/

                $sql="update clientes set telefono_cliente=?, correo_cliente=?, 
					direccion_cliente=?, estado=?, id_usuario=? where cedula_cliente=?";

                $sql=$conectar->prepare($sql);       
                $sql->bindValue(1, $_POST["telefono"]);
                $sql->bindValue(2, $_POST["email"]);
                $sql->bindValue(3, $_POST["direccion"]);
                $sql->bindValue(4, $_POST["estado"]);
                $sql->bindValue(5, $_POST["id_usuario"]);
                $sql->bindValue(6, $_POST["cedula_cliente"]);
                $sql->execute();
            }
        }

        /**
         * Metodo que retorna un cliente 
         * de la tabla de la BD
         * este metodo es para validar el id del cliente(luego llamamos el metodo de editar_estado()) 
         * el id_cliente se envia por ajax cuando se editar el boton cambiar estado 
         * y que se ejecuta el evento onclick y llama la funcion de javascript
         */
        public function get_cliente_id($id_cliente){
            $conectar=parent::conexion();
            parent::set_names();

            $sql="select * from clientes where id_cliente=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$id_cliente);
            $sql->execute();
            return $sql->fetchAll();
        }
		
		//método para mostrar los datos de un registro por usuario
        public function get_cliente_idusuario($id_usuario){
            $conectar= parent::conexion();
            parent::set_names();

            $sql="select * from clientes where id_usuario=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $id_usuario);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Metodo que edita el estado del cliente
         * activa y desactiva el estado
         */
        public function editar_estado($id_cliente,$estado){
            $conectar=parent::conexion();
            parent::set_names();

            //Verifica el valor del parametro que se envia por ajax
            if($_POST["est"]=="0"){
                $estado = 1;
            }else{
                $estado = 0;
            }

            $sql="update clientes set estado=? where id_cliente=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$id_cliente);
            $sql->bindValue(2,$estado);
            $sql->execute();

        }

        /** método si el cliente existe en la base de datos
           * valida si existe la cedula, cliente o correo, 
            * si existe entonces se hace el registro del cliente
        **/
        public function get_datos_cliente($cedula,$cliente,$correo){
            $conectar=parent::conexion();
            parent::set_names();

            $sql= "select * from clientes where cedula_cliente=? or
                     nombre_cliente=? or correo_cliente=?";
 
             $sql=$conectar->prepare($sql);
             $sql->bindValue(1, $cedula);
             $sql->bindValue(2, $cliente);
             $sql->bindValue(3, $correo);
             $sql->execute();
 
            //print_r($email); exit();
 
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

         //metodo que valida si hay registros activos
        public function get_cliente_estado($id_cliente,$estado){
            $conectar= parent::conexion();
            parent::set_names();
            
            $estado=1;//declaramos que el estado esté activo, igual a 1
             
           $sql="select * from clientes where id_cliente=? and estado=?";
   
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $id_cliente);
            $sql->bindValue(2, $estado);
            $sql->execute();
   
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        //método para mostrar los datos de un registro a modificar
        public function get_cliente_cedula($cedula){ 
            $conectar= parent::conexion();
            parent::set_names();

            $sql="select * from clientes where cedula_cliente=?";

            $sql=$conectar->prepare($sql);

            $sql->bindValue(1, $cedula);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        //ELIMINAR CLIENTE
        public function eliminar_cliente($id_cliente){
            $conectar=parent::conexion();
            parent::set_names();

            $sql="delete from clientes where id_cliente=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $id_cliente);
            $sql->execute();

            return $sql->fetch(PDO::FETCH_ASSOC);
        }
		
		//consulta si la cedula del cliente con tiene un detalle_venta asociado
		public function get_cliente_cedula_ventas($cedula_cliente){           
            $conectar=parent::conexion();
            parent::set_names();

            $sql="select c.cedula_cliente,v.cedula_cliente from clientes c             
              INNER JOIN ventas v ON c.cedula_cliente=v.cedula_cliente
              where c.cedula_cliente=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$cedula_cliente);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);

        }

        //consulta si el id del cliente tiene un detalle_venta asociado
        public function get_cliente_cedula_detalle_ventas($cedula_cliente){          
            $conectar=parent::conexion();
            parent::set_names();

            $sql="select c.cedula_cliente,d.cedula_cliente from clientes c             
              INNER JOIN detalle_ventas d ON c.cedula_cliente=d.cedula_cliente
              where c.cedula_cliente=? ";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$cedula_cliente);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);
      
       }

    }

?>