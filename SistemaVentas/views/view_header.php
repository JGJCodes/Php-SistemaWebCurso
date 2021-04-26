<!--
  Archivo que contiene la definicion del
  encabezado de las paginas del sistema
-->
<?php

   /*validamos si no existe una variable de session entonces 
   el id de session es menor que 1 entonces iniciaria la session
   y si ya existen una session iniciada entonces no vamos hacer
   nada, esto para omitir algunos errores de que si ya ha 
   existido la session anteriormente*/
   if(strlen(session_id()) < 1)
      session_start();

	  require_once("../config/conexion.php");

      if(isset($_SESSION["id_usuario"])){
		  /*Se llaman los modelos y se crean los objetos para llamar el numero de registros en el menu lateral izquierdo y en el home*/
          require_once("../models/categoria.php");
          require_once("../models/producto.php");
          require_once("../models/proveedor.php");
          require_once("../models/usuario.php");
          require_once("../models/compra.php");
          require_once("../models/cliente.php");
          require_once("../models/venta.php");

		   $categoria = new Categoria();
		   $producto = new Producto();
		   $proveedor = new Proveedor();
		   $compra = new Compras();
		   $cliente = new Cliente();
		   $venta = new Ventas();
		   $usuario = new Usuarios();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Sistema Compra - Venta </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../public/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../public/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../public/bower_components/Ionicons/css/ionicons.min.css">

  <!-- DataTables -->
  <!--<link rel="stylesheet" href="../public/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">-->
  <link rel="stylesheet" href="../public/plugins/datatables/jquery.datatables.min.css">
  <link rel="stylesheet" href="../public/plugins/datatables/buttons.datatables.min.css">
  <link rel="stylesheet" href="../public/plugins/datatables/responsive.datatables.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="../public/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../public/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../public/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../public/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../public/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../public/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!--ESTILOS-->
  <link rel="stylesheet" href="../public/estilos/estilos.css">

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Compra - Venta</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
        
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             
             <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
              <i class="fa fa-user" aria-hidden="true"></i>
              <span class="hidden-xs"> <?php echo $_SESSION["nombre"]?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <!--<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->

                 <i class="fa fa-user" aria-hidden="true"></i>

                <p>
                <?php echo $_SESSION["nombre"]?> - Web Developer
               <!--   <small>Administrador desde Noviembre 2017</small>-->
                </p>
              </li>
              <!-- Menu Body -->
              <!--<li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>-->
                <!-- /.row -->
              <!--</li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                <a href="#" class="btn btn-default btn-flat" 
                onclick="mostrar_perfil('<?php echo $_SESSION["id_usuario"]?>')"  
                        data-toggle="modal" data-target="#perfilModal" > Perfil de usuario </a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Cerrar sesión</a>
                </div>
              </li>
            </ul>
          </li>
       
        </ul>
      </div>
    </nav>
  </header>

  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <li class="">
          <a href="view_home.php">
            <i class="fa fa-home" aria-hidden="true"></i> <span>Inicio</span>
          </a>
          
        </li>
		
		<?php 
		
		if($_SESSION["categoria"]==1){

            echo '<li class="">

              <a href="view_categoria.php">
                <i class="fa fa-list" aria-hidden="true"></i> <span>Categoría</span>
                <span class="pull-right-container badge bg-blue">
                  <i class="fa fa-bell pull-right">'.$categoria->get_filas_categoria().'</i>
                </span>
              </a>
         
          </li>';
          }
         ?>

        <?php 
		
		if($_SESSION["productos"]==1){

            echo ' <li class="">
			<a href="view_producto.php">
            <i class="fa fa-tasks" aria-hidden="true"></i> <span>Productos</span>
            <span class="pull-right-container badge bg-blue">
              <i class="fa fa-bell pull-right">'.$producto->get_filas_producto().'</i>
            </span>
			</a>
         
			</li>';

          }
        ?>


		<?php 
		
		if($_SESSION["proveedores"]==1){

            echo ' <li class="">
				<a href="view_proveedor.php">
                <i class="fa fa-users"></i> <span>Proveedores</span>
                <span class="pull-right-container badge bg-blue">
                  <i class="fa fa-bell pull-right">'.$proveedor->get_filas_proveedor().'<</i>
                </span>
              </a>

			</li>';

          }
        ?>

        <?php 
		
		if($_SESSION["compras"]==1){

            echo '<li class="treeview">
          <a href="view_compras.php">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Compras</span>
            <span class="pull-right-container badge bg-blue">
              <i class="fa fa-bell pull-right">'.$compra->get_filas_compra().'<</i>
              <i class="fa fa-angle-left pull-right"></i>  <!-- icono de submenu -->
            </span>
          </a>
         <!-- Submenu de opciones del modulo compras -->
          <ul class="treeview-menu">
            <li><a href="view_compras.php"><i class="fa fa_circle-o"></i>Compras</a></li>
            <li><a href="view_compras_consultar.php"><i class="fa fa_circle-o"></i>Consultar compras</a></li>
            <li><a href="view_compras_fecha.php"><i class="fa fa_circle-o"></i>Consultar compras por fecha</a></li>
            <li><a href="view_compras_mes.php"><i class="fa fa_circle-o"></i>Consultar compras por mes</a></li>
          </ul>

        </li>';

          }
        ?>

        <?php 
		
		if($_SESSION["clientes"]==1){

            echo '<li class="">
          <a href="view_clientes.php">
            <i class="fa fa-users"></i> <span>Clientes</span>
            <span class="pull-right-container badge bg-blue">
              <i class="fa fa-bell pull-right">'.$cliente->get_filas_cliente().'</i>
            </span>
          </a>
         
        </li>';

          }
        ?>
		
		
		 <?php 
		
		if($_SESSION["clientes"]==1){

            echo '
          <!-- clase treeview de submenu -->
         <li class="treeview">
          <a href="view_ventas.php">
            <i class="fa fa-suitcase" aria-hidden="true"></i> <span>Ventas</span>
            <span class="pull-right-container badge bg-blue">
              <i class="fa fa-bell pull-right">'.$venta->get_filas_venta().'</i>
              <i class="fa fa-angle-left pull-right"></i>  <!-- icono de submenu -->
            </span>
          </a>
         
          <!-- Submenu de opciones del modulo ventas -->
          <ul class="treeview-menu">
            <li><a href="view_ventas.php"><i class="fa fa_circle-o"></i>Ventas</a></li>
            <li><a href="view_ventas_consultar.php"><i class="fa fa_circle-o"></i>Consultar ventas</a></li>
            <li><a href="view_ventas_fecha.php"><i class="fa fa_circle-o"></i>Consultar ventas por fecha</a></li>
            <li><a href="view_ventas_mes.php"><i class="fa fa_circle-o"></i>Consultar ventas por mes</a></li>
          </ul>

         </li>';

          }
        ?>


		 <?php 
		
		if($_SESSION["reporte_compras"]==1){

            echo '
        <li class="treeview">
          <a href="view_reportes_compras.php">
            <i class="fa fa-bar-chart" aria-hidden="true"></i> <span>Reportes de compras</span>
            <span class="pull-right-container badge bg-blue">
              <i class="fa fa-angle-left pull-right">
            </span>
          </a>
          <!-- Submenu de opciones del modulo compras -->
          <ul class="treeview-menu">
            <li><a href="view_reporte_compras_general.php"><i class="fa fa_circle-o"></i>Reporte general de compras</a></li>
            <li><a href="view_reporte_compras_mensual.php"><i class="fa fa_circle-o"></i>Reporte de compras mensuales </a></li>
            <li><a href="view_reporte_compras_proveedor.php"><i class="fa fa_circle-o"></i>Reporte de compras por proveedor</a></li>
           
          </ul>
         </li>';

          }
        ?>
		
		
		 <?php 
		
		if($_SESSION["reporte_ventas"]==1){

            echo '
        <li class="treeview">
          <a href="view_reportes_ventas.php">
            <i class="fa fa-pie-chart" aria-hidden="true"></i> <span>Reportes de ventas</span>
            <span class="pull-right-container badge bg-blue">
              <i class="fa fa-angle-left pull-right">
            </span>
          </a>
          <!-- Submenu de opciones del modulo ventas -->
          <ul class="treeview-menu">
            <li><a href="view_reporte_ventas_general.php"><i class="fa fa_circle-o"></i>Reporte general de ventas</a></li>
            <li><a href="view_reporte_ventas_mensual.php"><i class="fa fa_circle-o"></i>Reporte de ventas mensuales </a></li>
            <li><a href="view_reporte_ventas_cliente.php"><i class="fa fa_circle-o"></i>Reporte de ventas por cliente</a></li>
           
          </ul>
         </li>';

          }
        ?>


		 <?php 
		
		if($_SESSION["usuarios"]==1){

            echo '
        <li class="">
          <a href="view_usuarios.php">
            <i class="fa fa-user" aria-hidden="true"></i> <span>Usuarios</span>
            <span class="pull-right-container badge bg-blue">
              <i class="fa fa-bell pull-right">'.$usuario->get_filas_usuario().'</i>
            </span>
          </a>
         
         </li>';

          }
        ?>

         
		 
		<!--<li class="">
          <a href="view_backup.php">
            <i class="fa fa-database" aria-hidden="true"></i> <span>BackUp</span>
            <span class="pull-right-container badge bg-blue">
              <i class="fa fa-bell pull-right">3</i>
            </span>
          </a>
         
         </li>--!>
		
		 <?php 
		
		if($_SESSION["empresa"]==1){

            echo '

        <li class="">
          <a href="" onclick="mostrar_empresa('<?php echo $_SESSION["id_usuario"]?>')">
            <i class="fa fa-building" aria-hidden="true"> <span>Empresa</span> </i>
          </a>
         </li>';

          }
        ?>
       
       
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

 

  <?php

    require_once("view_perfil.php"); //FORMULARIO PERFIL USUARIO MODAL-->
    require_once("view_empresa.php"); //VISTA MODAL PARA EDITAR EMPRESA-->
	
	?>
	
	<script src="../public/bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="controllers/cont_perfil.js"></script> 
	<script type="text/javascript" src="controllers/cont_empresa.js"></script>
	
	<?php
     
    } else {

       header("Location:".Conectar::ruta()."index.php");
       exit();
    }
 ?>
