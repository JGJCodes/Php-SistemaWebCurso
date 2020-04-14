<?php
/**
 * Archivo que contiene la clase Producto
 * con las funciones de crear,editar,borrar
 * consultar registros en la base de datos
 */
 
 require_once("../config/conexion.php");//conexión a la base de datos

 class Producto extends Conectar{

    //método para retornar una lista de todos los registros
    public function get_productos(){
        $conectar= parent::conexion();
        parent::set_names();
     
        $sql= "select p.id_producto,p.id_categoria,p.producto,p.presentacion,p.unidad, p.moneda,
                p.precio_compra, p.precio_venta, p.stock, p.estado, p.imagen,
                p.fecha_vencimiento as fecha_vencimiento,c.id_categoria, c.categoria as categoria
                from producto p INNER JOIN categoria c ON p.id_categoria=c.id_categoria";

        $sql=$conectar->prepare($sql);

        $sql->execute();

         return $sql->fetchAll(PDO::FETCH_ASSOC); 
    }

    //método para seleccionar los registros para el formulario de ventas
    public function get_productos_ventas(){
        $conectar= parent::conexion();
        parent::set_names();
     
        $sql= "select p.id_producto,p.id_categoria,p.producto,
                p.presentacion,p.unidad, p.moneda, p.precio_compra,
                p.precio_venta, p.stock, p.estado, p.imagen, 
                p.fecha_vencimiento as fecha_vencimiento,
                c.id_categoria, c.categoria as categoria
                from producto p INNER JOIN categoria c ON p.id_categoria=c.id_categoria
                where p.stock > 0 and p.estado='1' ";

        $sql=$conectar->prepare($sql);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC); 
    }

    /*Metodo que realiza el guardado de una imagen en el sistema
    poner la ruta vistas/upload
    */
    public function upload_image() {
        if(isset($_FILES["producto_imagen"])){
            $extension = explode('.', $_FILES['producto_imagen']['name']);
            $new_name = rand() . '.' . $extension[1];
            $destination = '../files/img/' . $new_name;
            move_uploaded_file($_FILES['producto_imagen']['tmp_name'], $destination);
            return $new_name;
        }
    }

    //método para insertar registros
    public function registrar_producto($id_categoria,$producto,$presentacion,$unidad,$moneda,
                                        $precio_compra,$precio_venta,$stock,$estado,$imagen,$id_usuario){
        $conectar=parent::conexion();
        parent::set_names();
         
       /**declaro que si el campo stock es vacio entonces seria un 0 en caso
        contrario se pondria el valor que se envia **/
        $stock = "";

        if($stock==""){
            $stocker=0;   
        } else {
            $stocker = $_POST["stock"];
        }

        //llamo a la funcion upload_image()
        require_once("producto.php");

        $imagen_producto = new Producto();   
        $image = '';
        if($_FILES["producto_imagen"]["name"] != ''){
            $image = $imagen_producto->upload_image();
        }

        //fecha 
        $date = $_POST["datepicker"];
        $date_inicial = str_replace('/', '-', $date);
        $fecha = date("Y-m-d",strtotime($date_inicial));

        $sql="insert into producto values(null,?,?,?,?,?,?,?,?,?,?,?,?);";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $_POST["categoria"]);
        $sql->bindValue(2, $_POST["producto"]);
        $sql->bindValue(3, $_POST["presentacion"]);
        $sql->bindValue(4, $_POST["unidad"]);
        $sql->bindValue(5, $_POST["moneda"]);
        $sql->bindValue(6, $_POST["precio_compra"]);
        $sql->bindValue(7, $_POST["precio_venta"]);
        $sql->bindValue(8, $stocker);
        $sql->bindValue(9, $_POST["estado"]);
        $sql->bindValue(10, $image);
        $sql->bindValue(11, $fecha);
        $sql->bindValue(12, $_POST["id_usuario"]);
        $sql->execute();

    }

    //obtiene el registro por id despues de editar
    public function get_producto_id($id_producto){
        $conectar= parent::conexion();
        parent::set_names();

        //$output = array();

        $sql="select * from producto where id_producto=?";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $id_producto);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //metodo para consultar si la tabla productos tiene registros asociados con categorias
    public function get_prod_por_id_cat($id_categoria){
        $conectar= parent::conexion();
        parent::set_names();
        //$output = array();
  
        $sql="select * from producto where id_categoria=?";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $id_categoria);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);   
    }

    //método para editar registro
    public function editar_producto($id_producto,$id_categoria,$producto,$presentacion,$unidad,$moneda,
                                    $precio_compra,$precio_venta,$stock,$estado,$imagen,$id_usuario){
        $conectar=parent::conexion();
        parent::set_names();

        /**declaro que si el campo stock es vacio entonces seria 
         un 0 en caso contrario se pondria el valor que se envia **/
        $stock = "";
        if($stock==""){       
            $stocker=0;
        } else {
            $stocker = $_POST["stock"];
        }

        //llamo a la funcion upload_image()
        require_once("producto.php");
        $imagen_producto = new Producto();
        $imagen = '';
        if($_FILES["producto_imagen"]["name"] != ''){
            $imagen = $imagen_producto->upload_image();
        }else{
            $imagen = $_POST["hidden_producto_imagen"];
        }

        //fecha 
        $fecha = $_POST["datepicker"];
        $date_inicial = str_replace('/', '-', $fecha);
        $fecha = date("Y-m-d",strtotime($date_inicial));

        $sql="update producto set id_categoria=?,producto=?,
            presentacion=?,unidad=?,moneda=?,precio_compra=?,precio_venta=?,
            stock=?,estado=?,imagen=?,fecha_vencimiento=? id_usuario=? where id_producto=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $_POST["categoria"]);
        $sql->bindValue(2, $_POST["producto"]);
        $sql->bindValue(3, $_POST["presentacion"]);
        $sql->bindValue(4, $_POST["unidad"]);
        $sql->bindValue(5, $_POST["moneda"]);
        $sql->bindValue(6, $_POST["precio_compra"]);
        $sql->bindValue(7, $_POST["precio_venta"]);
        $sql->bindValue(8, $stocker);
        $sql->bindValue(9, $_POST["estado"]);
        $sql->bindValue(10, $imagen);
        $sql->bindValue(11, $fecha);
        $sql->bindValue(12, $_POST["id_usuario"]);
        $sql->bindValue(13, $_POST["id_producto"]);
        $sql->execute();
    }
    
    //método para activar Y/0 desactivar el estado del producto
    public function editar_estado($id_producto,$estado){
        $conectar=parent::conexion();
        parent::set_names();
                  
        /**si estado es igual a 0 entonces lo cambia a 1
        //el parametro est viene por via ajax, viene de est:est**/
        $estado = 0;
        if($_POST["est"] == 0){
            $estado = 1;
        }

        $sql="update producto set estado=? where id_producto=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $estado);
        $sql->bindValue(2, $id_producto);
        $sql->execute();
    }

    //método para editar el estado del producto por la categoria
    public function editar_estado_producto($id_categoria,$estado){
        $conectar=parent::conexion();
        parent::set_names();
                  
        /**si estado es igual a 0 entonces lo cambia a 1
        //el parametro est viene por via ajax, viene de est:est**/
        $estado = 0;
        if($_POST["est"] == 0){
            $estado = 1;
        }

        $sql="update producto set estado=? where id_categoria=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $estado);
        $sql->bindValue(2, $id_categoria);
        $sql->execute();
    }

    //método para editar el estado de la categoria por el producto
    public function editar_estado_categoria($id_categoria,$estado){
        $conectar=parent::conexion();
        parent::set_names();
                  
        /**si estado es igual a 0 entonces lo cambia a 1
        //el parametro est viene por via ajax, viene de est:est**/
        $estado = 0;
        if($_POST["est"] == 0){
            $estado = 1;
            $sql="update categoria set estado=? where id_categoria=?";

            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $estado);
            $sql->bindValue(2, $id_categoria);
            $sql->execute();
        }
    }

    //método para obtener los datos de un producto por su nombre
    public function get_producto_nombre($producto){
        $conectar=parent::conexion();
        parent::set_names();

        $sql= "select * from producto where producto=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $producto);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //metodo que valida si hay registros activos
    public function get_producto_estado($id_producto,$estado){
        $conectar=parent::conexion();
        parent::set_names();

        $sql= "select * from producto where id_producto=? and estado=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $id_producto);
        $sql->bindValue(2, $estado);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //consulta si el id del producto con tiene un detalle_compra asociado
    public function get_producto_por_id_detalle_compra($id_producto){        
        $conectar=parent::conexion();
        parent::set_names();

        $sql="select p.id_producto,p.producto,c.id_producto, c.producto as producto_compras
          from producto p INNER JOIN detalle_compras c ON p.id_producto=c.id_producto where p.id_producto=?";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1,$id_producto);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    //consulta si el id del producto con tiene un detalle_venta asociado
    public function get_producto_por_id_detalle_venta($id_producto){           
        $conectar=parent::conexion();
        parent::set_names();

        $sql="select p.id_producto,p.producto, v.id_producto, v.producto as producto_ventas      
          from producto p INNER JOIN detalle_ventas v ON p.id_producto=v.id_producto where p.id_producto=? ";

        $sql=$conectar->prepare($sql);
        $sql->bindValue(1,$id_producto);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    //metodo que realiza la eliminacion de un producto
    public function eliminar_producto($id_producto){
       $conectar=parent::conexion();
       parent::set_names();
        
       $sql="delete from producto where id_producto=?";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $id_producto);
        $sql->execute();

        return $resultado=$sql->fetch(PDO::FETCH_ASSOC);
    }
     
 }
