<?php
/**
 * Archivo que define la clase empresa
 * con las funciones de iniciar sesion,
 * registrar,borrar,actualizar,consultar
 * y editar los datos en la base de datos
 */

 require_once("../config/conexion.php");//requiere la conexion con la BD

    /** 
     * Clase que define a la tabla Empresas 
     * */
    class Empresas extends Conectar{
    
        /**
         * Metodo que enlista todos los registros 
         * de la tabla clientes de la BD
         */
        public function get_empresa(){
            $conectar=parent::conexion();
            parent::set_names();
 
            $sql="select * from empresa;";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
       }
	   
	   //retorna el numero total de registros en la tabla empresa
	  public function get_filas_empresa(){
			$conectar= parent::conexion();        
			$sql="select * from empresa";         
			$sql=$conectar->prepare($sql);
			$sql->execute();
			$resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
			return $sql->rowCount();
      }
 
      //metodo muestra la informacion de la empresa por usuario
       public function get_empresa_idusuario($id_usuario_empresa){
 
            $conectar= parent::conexion();
            parent::set_names();
            
            $sql="select * from empresa where id_usuario=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $id_usuario_empresa);
            $sql->execute();
 
            return $sql->fetchAll(PDO::FETCH_ASSOC);
       }

        //metodo muestra la informacion de la empresa por empresa
        public function get_datos_empresa($cedula,$cliente, $correo){
            $conectar=parent::conexion();
            parent::set_names();

            $sql= "select * from empresa where cedula_empresa=? or nombre_empresa=? or correo_empresa=?";
 
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $cedula);
            $sql->bindValue(2, $cliente);
            $sql->bindValue(3, $correo);
            $sql->execute();
            return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
        }
 
        //metodo edita los valores de un registro
        public function editar_empresa($id_usuario_empresa,$nombre,$cedula,$telefono,$email,$direccion){
           $conectar=parent::conexion();
           parent::set_names();

           $sql="update empresa set cedula_empresa=?, nombre_empresa=?,direccion_empresa=?,
                  correo_empresa=?, telefono_empresa=? where id_usuario=? ";
 
           $sql=$conectar->prepare($sql);
           $sql->bindValue(1,$_POST["cedula_empresa"]);
           $sql->bindValue(2,$_POST["nombre_empresa"]);
           $sql->bindValue(3,$_POST["direccion_empresa"]);
           $sql->bindValue(4,$_POST["email_empresa"]);
           $sql->bindValue(5,$_POST["telefono_empresa"]);
           $sql->bindValue(6,$_POST["id_usuario_empresa"]);
           $sql->execute();
           $resultado=$sql->fetch(PDO::FETCH_ASSOC);
        }
    }

?>