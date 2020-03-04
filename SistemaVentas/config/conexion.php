<?php

    class Conectar{
        protected $dbh; // atributo que almacena la conexion mysql

        /**Metodo que realiza la conexion con la Base de Datos
         * local=direccion del servidor
         * root = usuario administrador
         * "" = contraseña del usuario
         */
        protected function conexion(){
            try{
                $conectar = $this-> dbh = new PDO("mysql:local=localhost;dbname=dbproyecto","root","");
    
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
         */
        public function ruta(){
            return "http://localhost/proyecto/";
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