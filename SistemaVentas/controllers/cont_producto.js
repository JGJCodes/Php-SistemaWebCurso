/**
 * Archivo que contiene los procesos de controlar
 * la informacion transmitida a la vista Producto
 */

var tabla;

var tabla_compras;

//Función que se ejecuta al inicio
function init() {
    listar();

    listar_compras(); //llama la lista de productos en ventana modal en compras.php

    //cuando se da click al boton submit entonces se ejecuta la funcion guardaryeditar(e);
    $("#producto_form").on("submit", function (e) {
        guardaryeditar(e);
    })

    //cambia el titulo de la ventana modal cuando se da click al boton
    $("#add_button").click(function () {
        $(".modal-title").text("Agregar Producto");
    });
}


//Función limpiar
/*IMPORTANTE: no limpiar el campo oculto del id_usuario, sino no se registra
la categoria*/
function limpiar() {
    $("#id_producto").val("");
    //$("#id_usuario").val("");
    $("#categoria").val("");
    $('#producto').val("");
    $('#presentacion').val("");
    $('#unidad').val("");
    $('#moneda').val("");
    $('#precio_compra').val("");
    $('#precio_venta').val("");
    $('#stock').val("");
    $('#estado').val("");
    $('#datepicker').val("");
    $('#producto_imagen').val("");
}

//Función Listar
function listar() {
    tabla = $('#producto_data').dataTable({
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdf'
            ],
            "ajax":{
                url: '../ajax/producto.php?op=listar',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "responsive": true,
            "bInfo": true,
            "iDisplayLength": 10,//Por cada 10 registros hace una paginación
            "order": [[0, "desc"]],//Ordenar (columna,orden)

            "language": {

                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }//cerrando language
        }).DataTable();
}

//Mostrar datos del producto en la ventana modal 
function mostrar(id_producto) {
    $.post("../ajax/producto.php?op=mostrar", { id_producto: id_producto }, function (data, status) {
        data = JSON.parse(data);

        //alert(data.cedula);
        $('#productoModal').modal('show');
        $('#categoria').val(data.categoria);
        $('#producto').val(data.producto);
        $('#presentacion').val(data.presentacion);
        $('#unidad').val(data.unidad);
        $('#moneda').val(data.moneda);
        $('#precio_compra').val(data.precio_compra);
        $('#precio_venta').val(data.precio_venta);
        $('#stock').val(data.stock);
        $('#estado').val(data.estado);
        $('#datepicker').val(data.fecha_vencimiento);
        $('.modal-title').text("Editar Producto");
        $('#id_producto').val(id_producto);
        $('#producto_uploaded_image').html(data.producto_imagen);
        $('#resultados_ajax').html(data);
        $("#producto_data").DataTable().ajax.reload();
    });
}


//la funcion guardaryeditar(e); se llama cuando se da click al boton submit
function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#producto_form")[0]);

    $.ajax({
        url: "../ajax/producto.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            /*bootbox.alert(datos);	          
            mostrarform(false);
            tabla.ajax.reload();*/
            //alert(datos);

            /*imprimir consulta en la consola debes hacer un print_r($_POST) al final del metodo 
               y si se muestran los valores es que esta bien, y se puede imprimir la consulta desde el metodo
               y se puede ver en la consola o desde el mensaje de alerta luego pegar la consulta en phpmyadmin*/
            console.log(datos);

            $('#producto_form')[0].reset();
            $('#productoModal').modal('hide');
            $('#resultados_ajax').html(datos);
            $('#producto_data').DataTable().ajax.reload();
            limpiar();
        }
    });
}

//EDITAR ESTADO DEL PRODUCTO
//importante:id_categoria, est se envia por post via ajax
function cambiarEstado(id_categoria, id_producto, est) {
    bootbox.confirm("¿Está Seguro de cambiar de estado?", function (result) {
        if (result) {
            $.ajax({
                url: "../ajax/producto.php?op=activarydesactivar",
                method: "POST",
                //data:dataString,
                //toma el valor del id y del estado
                data: { id_categoria: id_categoria, id_producto: id_producto, est: est },
                //cache: false,
                //dataType:"html",
                success: function (data) {
                    $('#producto_data').DataTable().ajax.reload();
                }
            });
        }
    });//bootbox
}

//Funcion listar productos en una ventana modal
function listar_compras(){
    tabla_compras=$('#lista_productos_data').dataTable({
        "aProcessing":true,//Activamos el procesamiento del datatables
        "aServerSide":true,//Paginacion y filtrado realizados por el servidor
        dom:'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax":{
            url:'../ajax/producto.php?op=listar_compras',
            type:"get",
            dataType:"json",
            error:function(e){
                console.log(e.responseText);
            }
        },
        "bDestroy":true,
        "responsive":true,
        "bInfo":true,
        "iDisplayLength": 10, //Por cada 10 registros hace una paginacion
        "order":[[0,"desc"]], //Ordenar (columna,orden)
        "language":{
            "sProcessing":"Procesando...",
            "sLengthMenu":"Mostrar _MENU_ registros",
            "sZeroRecords":"No se encontraron resultados",
            "sEmptyTable":"Ningún dato disponible en esta tabla",
            "sInfo":"Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty":"Mostrando un total de 0 registros",
            "sInfoFiltered":"(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":"",
            "sSearch":"Buscar:",
            "sUrl":"",
            "sInfoThousands":",",
            "sLoadingRecords":"Cargando...",
            "oPaginate":{
                "sFirst":"Primero",
                "sLast":"Ultimo",
                "sNext":"Siguiente",
                "sPrevious":"Anterior",
            },
            "oAria":{
                "sSortAscending":": Activar para ordenar la columna de forma ascendente",
                "sSortDescending":": Activar para ordenar la columna de forma descendente",
            }
        }//Fin language
    }).DataTable();
}//Fin funcion listar_compras

var detalles = [];//este es un arreglo vacio

/**IMPORTANTE function agregarDetalle y function listarDetalles:
*	Asi que detalles pertenece al arreglo detalles[]
*	Para agregar elementos a un arreglo en javascript, se utiliza el metodo push()
*   Puedes agregar variables u objetos, lo que yo he hecho es agregarle un objeto 
*    y ese objeto que contiene mucha informacion, que sería como una fila con muchas columnas
*	Para crear un objeto de ese tipo (con columnas), se utliliza esto :
*	var obj = { }
*	Dentro de las llaves definas columna y valor (Todo esto para una fila)
*	Lo defines asi:
*	nombre_columna : valor
*	El lenght 
*	sirve para calcular la longitud del arreglo o el 
*	numero de objetos que tiene el arreglo, que es lo mismo Y es por eso que 
*	lo necesito en el for. Claro que puedes agregarle un id y name al td**/
function agregarDetalle(id_producto,producto, estado){
	 	//alert(estado);
		$.ajax({
			url:"../ajax/producto.php?op=buscar_producto",
			method:"POST",
            data:{id_producto:id_producto, producto:producto, estado:estado},
			cache: false,
			dataType:"json",

			success:function(data){
                if(data.id_producto){
					if (typeof data == "string"){
						data = $.parseJSON(data);
					}
					console.log(data);
		                
		     /**IMPORTANTE: var obj: es un objeto con mucha informacion que contiene una fila con muchas columnas
			*	Para crear un objeto de ese tipo (con columnas), se utliliza esto :
		    *   var obj = { }, Dentro de las llaves definas columna y valor (Todo esto para una fila)
			*	Lo defines asi:
			*	nombre_columna : valor 
			*	este var obj es un objeto que trae la informacion de la data (../ajax/producto.php?op=buscar_producto)**/
						var obj = {
							cantidad : 1,
							codProd  : id_producto,
							codCat   : data.id_categoria,
							producto : data.producto,
							moneda   : data.moneda,
							precio   : data.precio_compra,
							stock    : data.stock,
							dscto    : 0,
							importe  : 0,
							estado   : data.estado
						};
		                
		 /**IMPORTANTE: detalles.push(obj);: Para agregar elementos a un arreglo en javascript,
          *  se utiliza el metodo push()
          * Puedes agregar variables u objetos, lo que yo he hechos es agregarle un objeto y 
          *   ese objeto que contiene mucha informacion,
          *  el detalles de detalles.push(obj); viene de detalles = [], una vez se agrega el 
          *  objeto al arreglo entonces se llama a la function listarDetalles(); 
			**/
						detalles.push(obj);
						listarDetalles();

						$('#lista_productosModal').modal("hide");

                       }//if validacion id_producto

                        else {
                        	 //si el producto está inactivo entonces se muestra una ventana modal
                            bootbox.alert(data.error);
                        }
					}//fin success		
				});//fin de ajax
}// fin de la funcion agregarDetalle

/**IMPORTANTE: El lenght 
*	sirve para calcular la longitud del arreglo o el 
*	numero de objetos que tiene el arreglo, que es lo mismo Y es por eso que 
*	lo necesito en el for**/
  function listarDetalles(){
  	$('#listProdCompras').html('');

  	var filas = "";
  	var subtotal = 0;
  	var total = 0;
    var subtotalFinal = 0;
  	var totalFinal = 0;
  	var iva = 20;
    var igv = (iva/100);

  	for(var i=0; i<detalles.length; i++){
		if( detalles[i].estado == 1 ){
	  	var importe = detalles[i].importe = detalles[i].cantidad * detalles[i].precio;
	  	importe = detalles[i].importe = detalles[i].importe - (detalles[i].importe * detalles[i].dscto/100);
        var filas = filas + "<tr><td>"+(i+1)+"</td> <td name='producto[]'>"+
                    detalles[i].producto+"</td> <td name='precio[]' id='precio[]'>"+
                    detalles[i].moneda+" "+detalles[i].precio+"</td> <td>"+detalles[i].stock+
                    "</td> <td><input type='number' class='cantidad input-group-sm' name='cantidad[]' "+
                    "id='cantidad[]' onClick='setCantidad(event, this, "+(i)+");' onKeyUp='setCantidad(event, this, "+
                    (i)+");' value='"+detalles[i].cantidad+"'></td>  <td><input type='number' name='descuento[]' "+
                    "id='descuento[]' onClick='setDescuento(event, this, "+(i)+");' onKeyUp='setDescuento(event, this, "+
                    (i)+");' value='"+detalles[i].dscto+"'></td> <td> <span name='importe[]' id='importe"+i+"'>"+
                    detalles[i].moneda+" "+detalles[i].importe+"</span> </td> <td>  <button href='#' " +
                    "class='btn btn-danger btn-lg' role='button' onClick='eliminarProd(event, "+(i)+
                    ");' aria-pressed='true'><span class='glyphicon glyphicon-trash'></span> </button></td> </tr>";
        subtotal = subtotal + importe;
        
		//concatenar para poner la moneda con el subtotal
        subtotalFinal = detalles[i].moneda+" "+subtotal;

		var su = subtotal*igv;
        var or=parseFloat(su);
        var total= Math.round(or+subtotal);

        //concatenar para poner la moneda con el total
        totalFinal = detalles[i].moneda+" "+total;  
		}//Fin estructura IF
	}//Fin ciclo for
	
    $('#listProdCompras').html(filas);
    
	//subtotal
	$('#subtotal').html(subtotalFinal);
    $('#subtotal_compra').html(subtotalFinal);
    
	//total
	$('#total').html(totalFinal);
	$('#total_compra').html(totalFinal);
}//Fin de la funcion listarDetalles



/**IMPORTANTE:Event es el objeto del evento que los manejadores de eventos utilizan
parseInt es una función para convertir un valor string a entero
obj.value es el valor del campo de texto**/
function setCantidad(event, obj, idx){
  	event.preventDefault();
  	detalles[idx].cantidad = parseInt(obj.value);
  	recalcular(idx);
  }

function setDescuento(event, obj, idx){
  	event.preventDefault();
  	detalles[idx].dscto = parseFloat(obj.value);
  	recalcular(idx);
}
      
/**Metodo que realiza la actualizacion de las sumas 
 * al agregar un producto en la compra**/
function recalcular(idx){
  	//alert('holaaa:::' + obj.value);
  	//var asd = document.getElementById('cantidad');
  	//console.log(detalles[idx].cantidad);
  	//detalles[idx].cantidad = parseInt(obj.value);
  	console.log(detalles[idx].cantidad);
  	console.log((detalles[idx].cantidad * detalles[idx].precio));
  	//var objImp = 'importe'+idx;
  	//console.log(objImp);
  	
  	/**IMPORTANTE:porque cuando agregaba una segunda fila el importe se alteraba?
       *  El importe se modificaba por que olvidé restarle el descuento
       * Así que solo agregué esa resta a la operación**/
  	var importe = detalles[idx].importe = detalles[idx].cantidad * detalles[idx].precio;
  	importe = detalles[idx].importe = detalles[idx].importe - (detalles[idx].importe * detalles[idx].dscto/100);
  	importeFinal = detalles[idx].moneda+" "+importe;

  	$('#importe'+idx).html(importeFinal);
  	calcularTotales();
}

//Metodo que realiza las operaciones aritmeticas de los totales de la compra
function calcularTotales(){
    var subtotal = 0;
    var total = 0;
    var subtotalFinal = 0;
  	var totalFinal = 0;
    var iva = 20;
    var igv = (iva/100);
      
	for(var i=0; i<detalles.length; i++){
  		if(detalles[i].estado == 1){
            subtotal = subtotal + (detalles[i].cantidad * detalles[i].precio) - 
                        (detalles[i].cantidad*detalles[i].precio*detalles[i].dscto/100);
		    
		    //concatenar para poner la moneda con el subtotal
            subtotalFinal = detalles[i].moneda+" "+subtotal;

            var su = subtotal*igv;
            var or=parseFloat(su);
            var total = Math.round(or+subtotal);

            //concatenar para poner la moneda con el total
            totalFinal = detalles[i].moneda+" "+total;
		}//Fin decision IF
    }//Fin ciclo FOR
    
	//subtotal
	$('#subtotal').html(subtotalFinal);
	$('#subtotal_compra').html(subtotalFinal);

	//total
	$('#total').html(totalFinal);
	$('#total_compra').html(totalFinal);
  }//Fin funcion calcularTotales


/**IMPORTANTE:Event es el objeto del evento que los manejadores de eventos utilizan
 * parseInt es una función para convertir un valor string a entero
 * obj.value es el valor del campo de texto**/
function  eliminarProd(event, idx){
  	event.preventDefault();
  	//console.log('ELIMINAR EYTER');
  	detalles[idx].estado = 0;
  	listarDetalles();
}

/**Funcion que registra un compra en el sistema
 * IMPORTANTE: se declaran las variables ya que se usan en el data, sino da error*/
function registrarCompra(){
    var numero_compra = $("#numero_compra").val();
    var cedula = $("#cedula").val();
    var razon = $("#razon").val();
    var direccion = $("#direccion").val();
    var total = $("#total").html();
    var comprador = $("#comprador").html();
    var tipo_pago = $("#tipo_pago").val();
    var id_usuario = $("#id_usuario").val();
    var id_proveedor = $("#id_proveedor").val();

    /**alert(usuario_id);
    validamos, si los campos(proveedor) estan vacios entonces no se envia el formulario**/

    if(cedula!="" && razon!="" && direccion!="" && tipo_pago!="" && detalles!=""){
     /**console.log(numero_compra);
     console.log(cedula);
     console.log(razon);
     console.log(direccion);
     console.log(datepicker);
     console.log('Hola Eyter');**/
    
    /**IMPORTANTE: el array detalles de la data viene de var detalles = []; esta vacio pero
     *  como ya se usó en la function agregarDetalle(id_producto,producto)
     * se reusa, pero ya viene cargado con la informacion que se va a enviar con ajax**/
    $.ajax({
		url:"../ajax/producto.php?op=registrar_compra",
		method:"POST",
        data:{'arrayCompra':JSON.stringify(detalles), 'numero_compra':numero_compra,
        'cedula':cedula,'razon':razon,'direccion':direccion,'total':total,'comprador':comprador,
        'tipo_pago':tipo_pago,'id_usuario':id_usuario,'id_proveedor':id_proveedor},
		cache: false,
		dataType:"html",
		error:function(x,y,z){
			console.log(x);
			console.log(y);
			console.log(z);
		},
         
/**IMPORTANTE:hay que considerar que esta prueba lo hice sin haber creado la funcion agrega_detalle_compra()
 * 
 * IMPORTANTE: para imprimir el sql en registrar_compra.php, se comenta el typeof y se descomenta console.log(data);
 * y en registrar_compra.php que seria la funcion agrega_detalle_compra(); comente $conectar,  $sql=$conectar->prepare($sql); y los parametros enumerados y $sql->execute(),
 * me quede solo con los parametros que estan en el foreach, la consulta $sql (insert) y el echo $sql, luego se me muestra un alert con la consulta 
 * lo que hice fue concatenar y meter las variables en la consulta y sustituirla por ? ejemplo $sql="insert into detalle_compra
 * values(null,'".$numero_compra."','".$producto."','".$precio."','".$cantidad."','".$dscto."','".$cedula_proveedor."','".$fecha_compra."');"; 
 * luego agrego un producto y creo la consulta con los valores reales por ejemplo insert into detalle_compra values(null,'F000001','ganchate','900','1','0','666666','01/01/1970'); y lo inserto en phpmyadmin
 * 
 * Antes hice un alert con estas variables (antes hay que llenar el formulario para poder mostrar los valores con el alert)
 * var numero_compra = $("#numero_compra").val();
 * var cedula = $("#cedula").val();
 * var razon = $("#razon").val();
 * var direccion = $("#direccion").val();
 * var datepicker = $("#datepicker").val();   **/
			
		success:function(data){
			//IMPORTANTE: esta se descomenta cuando imprimo el console.log
			/*if (typeof data == "string"){
			      data = $.parseJSON(data);
			}*/
			console.log(data);
             
			//alert(data);
            //IMPORTANTE:limpia los campos despues de enviarse
            //cuando se imprime el alert(data) estas variables deben comentarse
		
			var cedula = $("#cedula").val("");
		    var razon = $("#razon").val("");
		    var direccion = $("#direccion").val("");
		    var subtotal = $("#subtotal").html("");
		    var total = $("#total").html("");
            
            detalles = [];
            $('#listProdCompras').html('');

            //1000-3000

          //muestra un mensaje de exito
          setTimeout ("bootbox.alert('Se ha registrado la compra con éxito');", 100); 
          
          //refresca la pagina, se llama a la funtion explode
          setTimeout ("explode();", 2000); 
		}
	});	//cierre del condicional de validacion de los campos del producto,proveedor,pago

	 } else{
	 	 bootbox.alert("Debe agregar un producto, los campos del proveedor y el tipo de pago");
	 	 return false;
	 } 	
}

 //RESFRESCA LA PAGINA DESPUES DE REGISTRAR LA COMPRA
function explode(){
    location.reload();
}



init();