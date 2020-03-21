/**
 * Archivo que contiene los procesos de controlar
 * la informacion transmitida a la vista Producto
 */

var tabla;

//Función que se ejecuta al inicio
function init() {
    listar();

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


init();