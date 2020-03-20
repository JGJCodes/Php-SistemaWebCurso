<?php
/**
 * Archivo que contiene la definicion de la clase 
 * Conectar que realiza los procesos de conexion 
 * con la base de datos del sistema
 */

    session_start(); //Inicia la variable de login

    class Conectar{
        protected $dbh; // atributo que almacena la conexion mysql

        /**Metodo que realiza la conexion con la Base de Datos
         * local=direccion del servidor
         * root = usuario administrador
         * "" = contraseña del usuario
         */
        protected function conexion(){
            try{
                $conectar = $this-> dbh = new PDO("mysql:local=localhost;dbname=dbproyecto","admincurso","admin123");
    
                return $conectar;
            }catch(Exception $e){
                print "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }

        } //Fin del metodo conexion

        /**Metodo que realiza la codificacion de los caracteres
         * Evita que existan errores con los acentos y tildes
         */
        public function set_names(){
            return $this->dbh->query("SET NAMES 'utf8'");
        }

        /**Metodo que muestra la ruta del proyecto
         * NOTA: se utiliza para direccionar a los usuarios
         * a la pagina de inicio durante el proceso de login
         */
        public function ruta(){
            return "http://localhost/SistemaVentas/";
        }

        /**Metodo que realiza la conexion y 
         * la codificacion de caracteres
         
        public static function set_conexion(){
            $conectar=parent::conexion();
            parent::set_names();
            return $conectar;
        }*/
        
    } //Fin de la clase conectar

   
?>