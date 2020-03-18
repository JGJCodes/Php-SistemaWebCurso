<?php
    require_once("../config/conexion.php"); //Incluir la clase conexion DB

    session_destroy(); //Destruye la sesion de usuario

    header("Location:".Conectar::ruta()."views/view_login.php"); //Redirige a la pagina de login
    exit();

?>