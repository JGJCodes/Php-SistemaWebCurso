<?php

    //requiere la conexion con la BD
    require_once("../config/conexion.php");

    /** 
     * Clase que define a la tabla usuario 
     * */
    class Usuarios extends Conectar{

        /**
         * Metodo que enlista todos los registros 
         * de la tabla usuario de la BD
         */
        public function get_usuarios(){
            $conectar=parent::conexion();//recoge la conexion con la BD
            parent::set_names();

            $sql = "select * from usuarios";//define la sentencia sql de consulta

            $sql = $conectar->prepare($sql);//transforma la sentencia string en sql
            $sql->execute();//ejecuta la sentencia sql 

            return  $sql->fetchAll();//devuelve los resultados de la consulta
        }

        /**
         * Metodo que registra un usuario 
         * en la tabla de la BD
         */
        public function registrar_usuario($nombre,$apellido,$cedula,$telefono,
        $email,$direccion,$cargo,$usuario,$password1,$password2,$estado){
            $conectar=parent::conexion();
            parent::set_names();

            $sql="insert into usuarios values(null,?,?,?,?,?,?,?,?,?,?,now(),?)";

            $sql=$conectar->prepare($sql);

            $sql->bindValue(1,$_POST["nombre"]);
            $sql->bindValue(2,$_POST["apellido"]);
            $sql->bindValue(3,$_POST["cedula"]);
            $sql->bindValue(4,$_POST["telefono"]);
            $sql->bindValue(5,$_POST["email"]);
            $sql->bindValue(6,$_POST["direccion"]);
            $sql->bindValue(7,$_POST["cargo"]);
            $sql->bindValue(8,$_POST["usuario"]);
            $sql->bindValue(9,$_POST["password1"]);
            $sql->bindValue(10,$_POST["password2"]);
            $sql->bindValue(11,$_POST["estado"]);
            $sql->execute();
        }

        /**
         * Metodo que actualiza un usuario 
         * en la tabla de la BD
         */
        public function editar_usuario($id_usuario,$nombre,$apellido,$cedula,$telefono,
        $email,$direccion,$cargo,$usuario,$password1,$password2,$estado){
            $conectar=parent::conexion();
            parent::set_names();

            $sql="update usuarios set
                nombre=?,
                apellido=?,
                cedula=?,
                telefono=?,
                email=?,
                direccion=?,
                cargo=?,
                usuario=?,
                password1=?,
                password2=?,
                estado=? where id_usuario=?";

            $sql=$conectar->prepare($sql);

            $sql->bindValue(1,$_POST["nombre"]);
            $sql->bindValue(2,$_POST["apellido"]);
            $sql->bindValue(3,$_POST["cedula"]);
            $sql->bindValue(4,$_POST["telefono"]);
            $sql->bindValue(5,$_POST["email"]);
            $sql->bindValue(6,$_POST["direccion"]);
            $sql->bindValue(7,$_POST["cargo"]);
            $sql->bindValue(8,$_POST["usuario"]);
            $sql->bindValue(9,$_POST["password1"]);
            $sql->bindValue(10,$_POST["password2"]);
            $sql->bindValue(11,$_POST["estado"]);
            $sql->bindValue(12,$_POST["id_usuario"]);
            $sql->execute();
        }

        /**
         * Metodo que retorna un usuario 
         * de la tabla de la BD
         */
        public function get_usuarioid($id_usuario){
            $conectar=parent::conexion();
            parent::set_names();

            $sql="select * from usuarios where id_usuario=?";

            $sql=$conectar->prepare($sql);

            $sql->bindValue(1,$id_usuario);
            $sql->execute();

            return $sql->fetchAll();
        }

        /**
         * Metodo que edita el estado del usuario
         * activa y desactiva el estado
         */
        public function editar_estado($id_usuario,$estado){
            $conectar=parent::conexion();
            parent::set_names();

            //Verifica el valor del parametro que se envia por ajax
            if($_POST["est"]=="0"){
                $estado = 1;
            }else{
                $estado = 0;
            }

            $sql="update usuarios set estado=? where id_usuario=?";

            $sql=$conectar->prepare($sql);

            $sql->bindValue(1,$estado);
            $sql->bindValue(2,$id_usuario);
            $sql->execute();

        }

        /**
         * Metodo que valida si el correo y cedula 
         * del usuario son unicos en la tabla para 
         * evitar duplicidad de usuarios
         */
        public function get_cedula_correo($cedula,$correo){
            $conectar=parent::conexion();
            parent::set_names();

            $sql="select * from usuarios where cedula=? or correo=?";

            $sql=$conectar->prepare($sql);

            $sql->bindValue(1,$cedula);
            $sql->bindValue(2,$correo);
            $sql->execute();

            return $sql->fetchAll();
        }
    }

?>