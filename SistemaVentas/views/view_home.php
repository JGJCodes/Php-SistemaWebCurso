<?php
    require_once("../config/conexion.php");

    //Evalua si se inicio una sesion
    if(isset($_SESSION["correo"])){

        require_once("view_header.php");
?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <h1>
            Inicio
        </h1>
        
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Contenedor de los modulos del sistema -->
        <div class="row panel_modulos"> 

            <div class="col-lg-3 col-xs-6">
            <!-- small box CLIENTES -->
            <div class="small-box bg-aqua">
                <div class="inner">

                <!-- Link que enlaza el contenedor a la pagina clientes -->
                <a href="<?php echo Conectar::ruta()?>views/view_clientes.php">
                    <!-- Numero de ventas -->
                    <h3>2</h3>

                    <h2>CLIENTES</h2>
                </a>
                </div>
                <div class="icon"> <!-- Incluir el icono del modulo -->
                    <i class="fa fa-users" aria-hidden="true""></i>
                </div>
            </div>
            </div>
            <!-- ./col -->


            <div class="col-lg-3 col-xs-6">
            <!-- small box VENTAS-->
            <div class="small-box bg-green">
                <div class="inner">
                    <!-- Link que enlaza el contenedor a la pagina ventas -->
                    <a href="<?php echo Conectar::ruta()?>views/view_ventas.php">
                         <!-- Numero de ventas -->
                        <h3>3</h3>
                        <h2>VENTAS</h2>
                    </a>
                </div>
                <div class="icon">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </div>
            </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-xs-6">
            <!-- small box PROVEEDORES -->
            <div class="small-box bg-yellow">
                <div class="inner">
                <!-- Link que redirige a la pagina proveedores -->
                <a href="<?php echo Conectar::ruta()?>views/view_proveedores.php">
                <!-- Numero de proveedores -->
                <h3>5</h3>
                
                <h2>PROVEEDORES</h2>
                </a>

                </div>
                <div class="icon">
                <i class="fa fa-truck" aria-hidden="true"></i>
                </div>
                
            </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-xs-6">
            <!-- small box COMPRAS -->
            <div class="small-box bg-red">
                <div class="inner">
                <!-- Link que redirige a la pagina compras -->
                <a href="<?php echo Conectar::ruta()?>views/view_compras.php">
                <!-- Numero de compras -->
                <h3>8</h3>
            
                <h2>COMPRAS</h2>
                </a>
                </div>
                <div class="icon">
                <i class="fa fa-cart-plus" aria-hidden="true"></i>
                </div>
            </div>
            </div>
            <!-- ./col -->
            
        </div><!--ROW-->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

<?php
    require_once("view_footer.php");

    
} else {
    header("Location:".Conectar::ruta()."views/view_login.php");
    exit();
}

?>