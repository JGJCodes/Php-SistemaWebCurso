<!--
  Archivo que contiene las estructuras
  de la pagina web que define la vista
  de la lista de las ventas por fecha
  del modulo ventas
-->

<?php
   require_once("../config/conexion.php"); //Conexion con la DB

    if(isset($_SESSION["id_usuario"])){

      require_once("view_header.php");
?>
  
   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Consulta de ventas por fecha</h1>
    </section>

    <!-- Main content -->
    <section class="content">

   <div id="resultados_ajax">     </div>

     <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">

              <div class="form-group">
                <label for="staticEmail" class="col-sm-2 col-form-label">Fecha Inicial</label>
                 <div class="col-sm-10">
                   <input type="text" class="form-control" id="datepicker" 
                   name="datepicker" placeholder="Fecha Inicial">
                 </div>
              </div>

              <div class="form-group">
                <label for="inputPassword" class="col-sm-2 col-form-label">Fecha Final</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="datepicker2" 
                  name="datepicker2" placeholder="Fecha Final">
                </div>
              </div>

               <div class="btn-group text-center">
                 <button type="button" class="btn btn-primary" id="btn_venta_fecha">
                     <i class="fa fa-search" aria-hidden="true"></i> Consultar</button>
               </div>
           </form>
       </div>
      </div>

       <!--VISTA MODAL PARA VER DETALLE VENTA EN VISTA MODAL-->
     <?php require_once("view_ventas_detalle.php");?>
    
      <div class="row">
        <div class="col-xs-12">  
          <div class="table-responsive">
            <div class="box-header">
              <h3 class="box-title">Lista de ventas por fecha</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <table id="ventas_fecha_data" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#A9D0F5">
                  <th>Ver Detalle</th>
                  <th>Fecha venta</th>
                  <th>Número venta</th>
                  <th>Cliente</th>
                  <th>Cédula Cliente</th>
                  <th>Vendedor</th>
                  <th>Tipo Pago</th>
                  <th>Total</th>
                  <th>Estado</th>                 
                </tr>
                </thead>
                
              </table>
            </div><!-- /.box-body -->
          </div> <!-- /.box -->
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </section><!-- /.content -->
  </div> <!-- /.content-wrapper -->

  <!--FIN DE CONTENIDO-->

<!--Footer Libreria-->
<?php require_once("view_footer.php");?>   

  <!--AJAX VENTAS-->
<script type="text/javascript" src="../controllers/cont_ventas.js"></script>

<?php
  } else {
        header("Location:".Conectar::ruta()."views/view_login.php");
  }
?>

