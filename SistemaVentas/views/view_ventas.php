<!--
  Archivo que contiene las estructuras
  de la pagina web que define la vista
  del formulario de registro de venta
  del modulo Ventas
-->

<?php
   require_once("../config/conexion.php"); //Conexion con la DB
   require_once("../models/venta.php"); //Modelo Ventas

    if(isset($_SESSION["id_usuario"])){
      $venta = new Ventas();

      require_once("view_header.php");
?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Realizar Ventas de Productos a Clientes</h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="panel panel-default">
        <div class="panel-body">
         <div class="btn-group text-center">
          <a href="view_ventas_consultar.php" class="btn btn-primary btn-lg" >
            <i class="fa fa-search" aria-hidden="true"></i> Consultar Ventas</a>
         </div>

       </div>
      </div>

    <section class="formulario-venta">
    <form class="form-horizontal" id="form_venta">
    <!--FILA PROVEEDOR - COMPROBANTE DE PAGO-->
     <div class="row">
        <div class="col-lg-8">
            <div class="box">
              <div class="box-body">
              <div class="form-group">
                  <div class="col-lg-6 col-lg-offset-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" 
                    data-target="#modalCliente"><i class="fa fa-search" aria-hidden="true">
                    </i>  Buscar Cliente</button>
                  </div>
                
              </div>

               <div class="form-group">
                  <label for="" class="col-lg-3 control-label">Número venta</label>

                  <div class="col-lg-9">
                     <input type="text" class="form-control" id="numero_venta" name="numero_venta" 
                    value="<?php $codigo=$venta->numero_venta();?>"  readonly>
                  </div>
              </div>


               <div class="form-group">
                  <label for="" class="col-lg-3 control-label">Cédula</label>

                  <div class="col-lg-9">
                    <input type="text" class="form-control" id="cedula" name="cedula"
                     placeholder="Cedula" required pattern="[0-9]{0,15}" readonly>
                  </div>
              </div>

              <div class="form-group">
                  <label for="" class="col-lg-3 control-label">Nombres</label>

                  <div class="col-lg-9">
                    <input type="text" class="form-control" id="nombre" name="nombre"
                     placeholder="Nombres" required pattern="^[a-zA-Z_áéíóúñ\s]{0,30}$" readonly>
                  </div>
              </div>

              <div class="form-group">
                  <label for="" class="col-lg-3 control-label">Apellidos</label>

                  <div class="col-lg-9">
                    <input type="text" class="form-control" id="apellido" name="apellido"
                     placeholder="Apellidos" required pattern="^[a-zA-Z_áéíóúñ\s]{0,30}$" readonly>
                  </div>
              </div>

               <div class="form-group">
                  <label for="" class="col-lg-3 control-label">Dirección</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" id="direccion" name="direccion"
                     placeholder="Direccion" required pattern="^[a-zA-Z0-9_áéíóúñ°\s]{0,200}$" readonly>
                  </div>
              </div>
               
              </div><!-- /.box-body -->
            <!--</form>-->
          </div><!-- /.box -->
        </div><!--fin col-lg-12-->  
     </div><!--fin row-->

     <!--FILA - PRODUCTO-->
     <div class="row">
        <div class="col-sm-12">
            <div class="box">
              <div class="box-body">
              <div class="row">
              
                  <div class="col-lg-3">
                     <div class="col-lg-5 text-center">
                     <button type="button" id="#" class="btn btn-primary" data-toggle="modal" 
                     data-target="#lista_productos_ventas_Modal"><i class="fa fa-plus" aria-hidden="true">
                     </i>  Agregar Productos</button>
                      </div>
                  </div>

                 <div class="col-lg-3">
                     <div class="col-lg-5">
                     <label for="">Vendedor: </label>
                      <h4 id="vendedor" name="vendedor"><?php echo $_SESSION["nombre"];?></h4>
                    </div>
                  </div>
    
                  <div class="col-lg-3">
                     <div class="">
           
                    <!--<label for=""><strong>Tipo de Pago:</strong> </label>-->
                    <h4 class="text-center"><strong>Tipo de Pago</strong></h4>

                    <select name="tipo_pago" class="col-lg-offset-3 col-xs-offset-2" id="tipo_pago" 
                    class="select" maxlength="10" >
                            <option value="">SELECCIONE TIPO DE PAGO</option>
                            <option value="CHEQUE">PAGAR CON CHEQUE</option>
                            <option value="EFECTIVO">PAGAR CON EFECTIVO</option>
                            <option value="TRANSFERENCIA">PAGAR CON TRANSFERENCIA</option>
                          </select>
                    </div>
                  </div>
                </div><!--fin row-->
              </div><!-- /.box-body -->  
          </div><!-- /.box -->
        </div><!--fin col-sm-6-->
     </div><!--fin row-->

    <div class="container box">
    <div class="row">
    <div class="col-lg-12">
        <div class="table-responsive"> 
          <!--<div class="box">-->
            <div class="box-header">
              <h3 class="box-title">Lista de Ventas a Clientes</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="detalles" class="table table-striped">
                <thead>
                 <tr class="bg-success">
                 <th class="all">Item</th>
                  <th class="all">Producto</th>
                  <th class="all">Precio Venta</th>
                  <th class="min-desktop">Stock</th>
                  <th class="min-desktop">Cantidad</th>
                  <th class="min-desktop">Descuento %</th>
                  <th class="min-desktop">Importe</th>
                  <th class="min-desktop">Acciones</th>
                  </tr>
                </thead>
                <div id="resultados_ventas_ajax"></div>
                 <tbody id="listProdventas">  
                </tbody>  
              </table>
            </div><!-- /.box-body -->
         
        </div><!-- /.table responsive -->
      </div> <!-- /.col -->

    </div><!-- /row -->
  </div> <!-- /container -->

      <!--TABLA SUBTOTAL - TOTAL -->
       <div class="row">
        <div class="col-xs-12">
          <div class="table-responsive">
            <div class="box-body">
              <table id="resultados_footer" class="table table-striped">
                <thead>
                <tr class="row bg-success">
                    <th class="col-lg-4">SUBTOTAL</th>
                    <th class="col-lg-4">I.V.A%</th>
                    <th class="col-lg-4">TOTAL</th>     
                </tr>
                </thead>

                <tbody>
                <tr class="row bg-gray">
                  <!--<td></td>
                  <td></td>
                  <td></td>-->

                  <td class="col-lg-4"><h4 id="subtotal"> 0.00</h4>
                  <input type="hidden" name="subtotal_venta" id="subtotal_venta"></td>

                  <td class="col-lg-4"><h4>20%</h4><input type="hidden"></td>
                   
                  <!--<td></td>-->
            <!--IMPORTANTE: hay que poner el name=total en el h4 para que lo pueda enviar,
             NO se envia si lo pones en el input hidden-->
            <td class="col-lg-4">
              <h4 id="total" name="total"> 0.00</h4>
              <input type="hidden" name="total_venta" id="total_venta"></td>
                   <!--<td></td>-->
                   
           
                 </tr>
                  
                  <tr class="">
                  <input type="hidden" name="grabar" value="si">
                  <input type="hidden" name="id_usuario" id="id_usuario"
                   value="<?php echo $_SESSION["id_usuario"];?>"/>
                   <input type="hidden" name="id_cliente" id="id_cliente"/>
                  
                 </tr>
            </tbody>
    </table>

            <div class="boton_registrar">
                <button type="button" onClick="registrarVenta()" 
                class="btn btn-primary col-lg-offset-10 col-xs-offset-3" id="btn">
                <i class="fa fa-save" aria-hidden="true"></i>  Registrar venta</button>
              </div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      </form>
      <!--formulario-pedido-->

      </section>
      <!--section formulario - pedido -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <!--FIN DE CONTENIDO-->


    <!--VISTA MODAL PARA AGREGAR CLIENTE-->
    <?php require_once("view_ventas_clientes.php");?>
    
    <!--VISTA MODAL PARA AGREGAR PRODUCTO-->
    <?php require_once("view_ventas_productos.php");?>



<!--Footer Libreria-->
<?php require_once("view_footer.php");?>   


 <!--AJAX CATEGORIAS-->
 <script type="text/javascript" src="../controllers/cont_categoria.js"></script>
   
   <!--AJAX CLIENTES-->
<script type="text/javascript" src="../controllers/cont_cliente.js"></script>

  <!--AJAX PRODUCTOS-->
<script type="text/javascript" src="../controllers/cont_producto.js"></script>

 <!--AJAX VENTAS-->
<script type="text/javascript" src="../controllers/cont_ventas.js"></script>


<?php
  } else {
        header("Location:".Conectar::ruta()."views/view_login.php");
  }
?>

