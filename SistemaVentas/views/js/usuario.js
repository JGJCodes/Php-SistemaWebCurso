var tabla;

//Funcion que se ejecuta al inicio
function init(){

}

//Funcion que limpia los campos del formulario
function limpiar(){
    $("#cedula").val("");
    $("#nombre").val("");
    $("#apellido").val("");
    $("#cargo").val("");
    $("#usuario").val("");
    $("#password1").val("");
    $("#password2").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#direccion").val("");
    $("#estado").val("");
    $("#id_usuario").val("");
}

//Funcion listar usuarios
function listar(){
    tabla=$('#usuario_data').dataTable({
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
            url:'../ajax/usuario.php?op=listar',
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

//Funcion para mostrar datos del usuario
function mostrar(id_usuario){
    $.post("../ajax/usuario.php?op=mostrar",
        {id_usuario : id_usuario}, 
        function(data,status){
            data= JSON.parse(data);
            $("#usuarioModal").modal("show");
            $("#cedula").val(data.cedula);
            $("#nombre").val(data.nombre);
            $("#apellido").val(data.apellido);
            $("#cargo").val(data.cargo);
            $("#usuario").val(data.usuario);
            $("#password1").val(data.password1);
            $("#password2").val(data.password2);
            $("#telefono").val(data.telefono);
            $("#email").val(data.correo);
            $("#direccion").val(data.direccion);
            $("#estado").val(data.estado);
            $(".modal-title").text("Editar Usuario");
            $("#id_usuario").val(id_usuario);
            $("#action").val(Edit);
        }
    );
}

init();