<!--
  Archivo que contiene las estructuras
  de la pagina web que define la vista
  del modulo Usuarios
-->

<?php
    require_once("../config/conexion.php");

    //Evalua si se inicio una sesion
    if(isset($_SESSION["id_usuario"])){
    
      require_once("view_header.php");
	  
	  //Verifica los permisos de acceso
	  if($_SESSION["usuarios"]==1){

?>

<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
             <!-- Contenedor de mensajes informativos de las consultas ajax-->
            <div id="resultados_ajax" class="alert alert-danger" role="alert"> </div> 

            <h2>Listado de Usuarios</h2>

            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title"><!-- boton de agregar usuario -->
                            <button class="btn btn-primary btn-lg" id="add_button"
                            datatoffle="modal" onclick="limpiar()" data-target="#usuarioModal"  >
                              <i class="fa fa-plus" aria-hidden="true">
                              </i> Nuevo Usuario</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="usuario_data" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>Cedula</th>
                              <th>Nombres</th>
                              <th>Apellidos</th>
                              <th>Usuario</th>
                              <th>Cargo</th>
                              <th>Telefono</th>
                              <th>Correo</th>
                              <th>Dirección</th>
                              <th>Fecha Ingreso</th>
                              <th>Estado</th>
                              <th width="10%">Editar</th>
                              <th width="10%">Eliminar</th>
                            </tr>
                          </thead>
                        </table>   

                    </div>
                  
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->

<!--FORMULARIO VENTANAN MODAL (Registro de usuario)-->
<div id="usuarioModal" class="modal fade">  
  <div class="modal-dialog">

    <form method="post" id="usuario_form">
      <div class="modal-content">

        <div class="modeal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar Usuario</h4>
        </div>

        <div class="modeal-body">
          <label>Cédula</label>
          <input type="text" name="cedula" id="cedula" class="form-control" placeholder="Cédula" required pattern="[0-9]{0,15}"/>
          <br />

          <label>Nombres</label>
          <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombres" required pattern="^[a-zA-Z_áéíóúñ\s]{0,30}$"/>
          <br />
          
          <label>Apellidos</label>
          <input type="text" name="apellido" id="apellido" class="form-control" placeholder="Apellidos" required pattern="^[a-zA-Z_áéíóúñ\s]{0,30}$"/>
          <br />
          
          <label>Cargo</label>
           <select class="form-control" id="cargo" name="cargo" required>
              <option value="">-- Selecciona cargo --</option>
              <option value="1" selected>Administrador</option>
              <option value="0">Empleado</option>
           </select>
           <br />
          
          <label>Usuario</label>
          <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario" required pattern="^[a-zA-Z_áéíóúñ\s]{0,30}$"/>
          <br />
          
          <label>Contraseña</label>
          <input type="password" name="password1" id="password1" class="form-control" placeholder="Password" required/>
          <br />
         
          <label>Confirmar contraseña</label>
          <input type="password" name="password2" id="password2" class="form-control" placeholder="Repita Password" required/>
          <br />
          
          <label>Teléfono</label>
          <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" required pattern="[0-9]{0,15}"/>
          <br />
          
          <label>Correo</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Correo" required="required"/>
          <br />
          
          <label>Dirección</label>
          <textarea cols="90" rows="3" id="direccion" name="direccion"  placeholder="Direccion ..." required pattern="^[a-zA-Z0-9_áéíóúñ°\s]{0,200}$">
          </textarea>
          <br />
          
          <label>Estado</label>
           <select class="form-control" id="estado" name="estado" required>
              <option value="">-- Selecciona estado --</option>
              <option value="1" selected>Activo</option>
              <option value="0">Inactivo</option>
           </select>
        </div>

        <div class="modal-footer">
          <input type="hidden" name="id_usuario" id="id_usuario"/> <!-- input que envia el id del usuario -->

          <button type="submit" name="action" id="btnGuardar" class="btn btn-success pull-left" value="Add">
            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button> <!-- boton que envia los datos-->

          <button type="button" onclick="limpiar()" class="btn btn-danger" data-dismiss="modal">
          <i class="fa fa-times" aria-hidden="true"></i> Cerrar</button> <!-- boton que cancela la operacion -->


        </div><!-- /.footer -->

      </div><!-- /.content -->
    </form> <!-- Fin de formulario -->
  </div>

</div><!-- Fin de formulario modal de usuario -->

<?php

	} else {

       require("sinacceso.php");
	}//CIERRE DE SESSION DE PERMISO

    require_once("view_footer.php");
?>

<!-- Archivo con las funciones de la clase usuario -->
<script text="text/javascript" src="../cotrollers/cont_usuario.js"></script>

<?php
    
} else {
    header("Location:".Conectar::ruta()."views/view_login.php");
    exit();
}

?>