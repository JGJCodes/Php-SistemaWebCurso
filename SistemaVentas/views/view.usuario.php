<?php
    require_once("view.header.php");
?>

<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">
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

<?php
    require_once("view.footer.php");
?>

<!-- Archivo con las funciones de la clase usuario -->
<script text="text/javascript" src="../cotrollers/controller.usuario.js"></script>
