<?php
/**
 * Archivo que contiene la clase Perfil
 * con las funciones de editar y consultar
 * el perfil de un usuario en la BD
 */
    //conexion a la base de datos
    require_once("../config/conexion.php");

    /** 
     * Clase que define a el perfil del usuario
     * */
    class Perfil extends Conectar{

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

        /**
         * Metodo que actualiza el perfil
         * de un usuario 
         **/
        public function editar_perfil($id_perfil,$nombre,$apellido,$cedula,$telefono,
        $email,$direccion,$usuario,$password1,$password2){
            $conectar=parent::conexion();
            parent::set_names();

            $sql="update usuarios set
                nombre=?,
                apellido=?,
                cedula=?,
                telefono=?,
                email=?,
                direccion=?,
                usuario=?,
                password=?,
                password2=?,
                 where id_usuario=?";

            echo $sql;  //Imprime la sentencia sql en la consola

            $sql=$conectar->prepare($sql);

            $sql->bindValue(1,$_POST["nombre"]);
            $sql->bindValue(2,$_POST["apellido"]);
            $sql->bindValue(3,$_POST["cedula"]);
            $sql->bindValue(4,$_POST["telefono"]);
            $sql->bindValue(5,$_POST["email"]);
            $sql->bindValue(6,$_POST["direccion"]);
            $sql->bindValue(8,$_POST["usuario"]);
            $sql->bindValue(9,$_POST["password1"]);
            $sql->bindValue(10,$_POST["password2"]);
            $sql->bindValue(12,$_POST["id_perfil"]);
            $sql->execute();

            //print_r($_POST); Imprime los resultados de la consulta en la pagina
        }

    }

?>