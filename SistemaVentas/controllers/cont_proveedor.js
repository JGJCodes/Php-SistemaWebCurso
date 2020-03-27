/**
 * Archivo que contiene los procesos de controlar
 * la informacion transmitida a la vista Proveedor
 */

var tabla;
var tabla_compras;

//Funcion que se ejecuta al inicio
function init(){
    listar();
    
    listar_compras();//llama la lista de proveedores en ventana modal en compras.php

	//cuando se da click al boton submit entonces se ejecuta la funcion guardaryeditar(e);
	$("#proveedor_form").on("submit",function(e){
		guardaryeditar(e);	
	})
    
    //cambia el titulo de la ventana modal cuando se da click al boton
	$("#add_button").click(function(){	
			$(".modal-title").text("Agregar Proveedor");
	  });
}

/*Función limpiar
IMPORTANTE: no limpiar el campo oculto del id_usuario, sino no se registra
la categoria*/
function limpiar(){
    $('#cedula').val("");
	$('#razon').val("");
	$('#telefono').val("");
	$('#email').val("");
	$('#direccion').val("");
	$('#datepicker').val("");
	$('#estado').val("");
	$('#cedula_proveedor').val("");
}

//Funcion listar proveedores
function listar(){
    tabla=$('#proveedor_data').dataTable({
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
            url:'../ajax/proveedor.php?op=listar',
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
}//Fin funcion listar usuario

//Funcion para mostrar datos del proveedor
function mostrar(cedula_proveedor){
    $.post("../ajax/proveedor.php?op=mostrar",
        {cedula_proveedor : cedula_proveedor}, 
        function(data,status){
            data= JSON.parse(data);
    		 //alert(data.cedula);
             $('#proveedorModal').modal('show');
             $('#cedula').val(cedula_proveedor);
             $('#razon').val(data.proveedor);
             $('#telefono').val(data.telefono);
             $('#email').val(data.correo);
             $('#direccion').val(data.direccion);
             $('#datepicker').val(data.fecha);
             $('#estado').val(data.estado);
             $('.modal-title').text("Editar Proveedor");
             $('#cedula_proveedor').val(cedula_proveedor);
        }
    );
}//Fin de la funcion mostrar

//La funcion guardaryeditar(e) es llamada cuando se da click al boton submit
function guardaryeditar(e){
    e.preventDefault(); //No se activara la accion predeterminada del evento
    var formData = new FormData($("#proveedor_form")[0]);
    
    //objeto ajax que realiza el proceso de guardar y editar datos
    $.ajax({
        url:"../ajax/proveedor.php?op=guardaryeditar",
        type:"POST",
        data:formData,
        contentType:false,
        processData:false,
        success: function(datos){
                /*bootbox.alert(datos);	          
		          mostrarform(false);
		          tabla.ajax.reload();
		         alert(datos);*/
                 
    /*imprimir consulta en la consola debes hacer un print_r($_POST) al final del metodo 
    y si se muestran los valores es que esta bien, y se puede imprimir la consulta desde el metodo
    y se puede ver en la consola o desde el mensaje de alerta luego pegar la consulta en phpmyadmin*/
                 
                console.log(datos);
                 $('#proveedor_form')[0].reset();
                 $('#proveedorModal').modal('hide');
                 $('#resultados_ajax').html(datos);
                 $('#proveedor_data').DataTable().ajax.reload();
                 limpiar(); //Llamada a la funcion limpiar del archivo
                }
    });//Fin de la validacion
    
}//Fin de la funcion guardaryeditar


/**
 * Funcion que cambia el estado del usuario con el id enviado por ajax
 * IMPORTANTE: id_proveedor,est se envian por POST via AJAX
 * **/
function cambiarEstado(id_proveedor,est){
    bootbox.confirm(
        "¿Está seguro de cambiar el estado?",
        function(result){

            if(result){
                $.ajax({
                    url:"../ajax/proveedor.php?op=activarydesactivar",
                    method:"POST",
                    //data:dataString,
				    //toma el valor del id y del estado
                    data:{ id_proveedor: id_proveedor, est:est }, //Toma el valor del id y estado
                   //cache: false,
				    //dataType:"html",
                    success: function(data){
                                $('#proveedor_data').DataTable().ajax.reload();
                            }//Fin de funcion 
                })
            }//Fin de la estructura if
        }//Fin de la funcion
    );//Fin del bootbox

}//Fin de la funcion cambiarEstado

//Funcion listar proveedores en una ventana modal
function listar_compras(){
    tabla_compras=$('#lista_proveedores_data').dataTable({
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
            url:'../ajax/proveedor.php?op=listar_compras',
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
}//Fin funcion listar usuario

//AUTOCOMPLETAR DATOS DEL PROVEEDOR EN COMPRAS
function agregar_registro(id_proveedor,est){
    $.ajax({
        url:"../ajax/proveedor.php?op=buscar_proveedor",
        method:"POST",
        data:{id_proveedor:id_proveedor,est:est},
        dataType:"json",
        success:function(data) {
                /*si el proveedor esta activo entonces se ejecuta, de lo contrario 
                el formulario no se envia y aparecerá un mensaje */
                if(data.estado){
                    $('#modalProveedor').modal('hide');
                    $('#cedula').val(data.cedula);
                    $('#razon').val(data.razon_social);
                    $('#direccion').val(data.direccion);
                    $('#datepicker').val(data.fecha);
                    $('#id_proveedor').val(id_proveedor);
                } else{
                    bootbox.alert(data.error);
                } //cierre condicional error 
            }
    })//cierre condicional error
}//cierre condicional error

init();