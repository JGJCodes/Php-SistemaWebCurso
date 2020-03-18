<?php

    //requiere la conexion con la BD
    require_once("../config/conexion.php");

    /** 
     * Clase que define a la tabla usuario 
     * */
    class Usuarios extends Conectar{

        /**
         * Metodo que realiza el proceso de 
         * identificacion o logeo del usuario
         */
        public function login(){
            $conectar=parent::conexion();//recoge la conexion con la BD
            parent::set_names();

            //Evalua si se realizo un envio de datos en el formulario
            if(isset($_POST["enviar"])){
                //recuperan los datos del intento de logeo
                $pass = $_POST["password"];
                $email = $_POST["correo"];
                $estado = "1";
                
                //Evalua si el correo y contraseña fueron ingresadas
                if(empty($email) and empty($pass)){
                    //Caso A: los datos son vacios
                    header("Location:".Conectar::ruta()."views/view_login.php?m=2");
                    exit();
                }
                else if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){12,15}$/",$pass)) {
                    //Caso B: los datos son incorrectos
                    header("Location:".Conectar::ruta()."views/view_login.php?m=1");
                    exit();
      
                  }else {
                    //Caso C: los datos son correctos y se realiza el logeo del usuario
                        $sql= "select * from usuarios where correo=? and password=? and estado=?";
      
                        $sql=$conectar->prepare($sql);
      
                        $sql->bindValue(1, $email);
                        $sql->bindValue(2, $pass);
                        $sql->bindValue(3, $estado);
                        $sql->execute();
                        $resultado = $sql->fetch();
      
                                //si existe el registro entonces se conecta en session
                            if(is_array($resultado) and count($resultado)>0){
      
                               /*IMPORTANTE: la session guarda los valores de los campos de la tabla de la bd*/
                             $_SESSION["id_usuario"] = $resultado["id_usuario"];
                             $_SESSION["correo"] = $resultado["correo"];
                             $_SESSION["cedula"] = $resultado["cedula"];
                             $_SESSION["nombre"] = $resultado["nombres"];
      
                              header("Location:".Conectar::ruta()."views/view_home.php");
      
                               exit();
      
                            } else {
                                
                                //si no existe el registro entonces le aparece un mensaje
                                header("Location:".Conectar::ruta()."views/view_login.php?m=1");
                                exit();
                             } 
                        
                    }//cierre del else
      
      
            }//condicion enviar
        }
            
          
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
                password=?,
                password2=?,
                estado=? where id_usuario=?";

            echo $sql;  //Imprime la sentencia sql en la consola

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

            print_r($_POST);// Imprime los resultados de la consulta en la pagina
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

            $sql->bindValue(1,$id_usuario);
            $sql->bindValue(2,$estado);
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