<!--
  Archivo que contiene las estructuras
  de la pagina web que define la vista
  del modulo Categorias
-->

<?php
    require_once("../config/conexion.php");

    //Evalua si se inicio una sesion
    if(isset($_SESSION["id_usuario"])){
    
      require_once("view_header.php");
?>

<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
             <!-- Contenedor de mensajes informativos de las consultas ajax-->
            <div id="resultados_ajax" class="alert alert-danger" role="alert"> </div> 

            <h2>Listado de Categorias</h2>

            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title"><!-- boton de agregar usuario -->
                            <button class="btn btn-primary btn-lg" id="add_button"
                            datatoffle="modal" onclick="limpiar()" data-target="#categoriaModal"  >
                              <i class="fa fa-plus" aria-hidden="true">
                              </i> Nueva categoria</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="categoria_data" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th width="8%">Categoria</th>
                              <th width="5%">Estado</th>
                              <th width="5%">Editar</th>
                              <th width="5%">Eliminar</th>
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

<!--FORMULARIO VENTANAN MODAL (Registro de categoria)-->
<div id="categoriaModal" class="modal fade">  
  <div class="modal-dialog">

    <form method="post" id="categoria_form">
      <div class="modal-content">

        <div class="modeal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar Categoria</h4>
        </div>

        <div class="modeal-body">
          <label>Categoria/label>
          <input type="text" name="categoria" id="categoria" class="form-control" 
            placeholder="Categoria" required pattern="^[a-zA-Z_áéíóúñ\s]{0,15}$"/>
          <br />

          <label>Estado</label>
           <select class="form-control" id="estado" name="estado" required>
              <option value="">-- Selecciona estado --</option>
              <option value="1" selected>Activo</option>
              <option value="0">Inactivo</option>
           </select>
           <br />

        </div>

        <div class="modal-footer">
          <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_usuario"];?>"/>
           <!-- input que envia el id del usuario -->

           <input type="hidden" name="id_categoria" id="id_categoria"/>
          
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
    require_once("view_footer.php");
?>

<!-- Archivo con las funciones de la clase usuario -->
<script text="text/javascript" src="../cotrollers/cont_categoria.js"></script>

<?php
    
} else {
    header("Location:".Conectar::ruta()."views/view_login.php");
    exit();
}

?>