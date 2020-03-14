var tabla;

//Funcion que se ejecuta al inicio
function init(){

    listar();
    //cuando se da click al boton submit se ejecuta la funcion guardaryeditar
    $("#usuario_form").on("submit",function(e){
                                    guardaryeditar(e)
                        }
    )

    //cambia el titulo de la ventana modal cuando se da click al boton
    $("#add_button").click(function(){
                            $(".modal-title").text("Agregar Usuario");
                            }
    );
    

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
}//Fin de la funcion mostrar

//La funcion guardaryeditar(e) es llamada cuando se da click al boton submit
function guardaryeditar(e){

    e.preventDefault(); //No se activara la accion predeterminada del evento
    var formData = new FormData($("#usuario_form")[0]);

    var password1 = $("#password1").val();
    var password2 = $("#password2").val();

    if(password1==password2){ //validar las contraseñas
        //objeto ajax que realiza el proceso de guardar y editar datos
        $.ajax({
            url:"../ajax/usuario.php?op=guardaryeditar",
            type:"POST",
            data:formData,
            contentType:false,
            processData:false,
            success: function(datos){
                        console.log(datos); //imprime el mensaje de error por consola

                        $('#usuario_form')[0].reset();
                        $('#usuarioModal').modal('hide');
                        $('#resultados_ajax').html(datos);
                        $('#usuario_data').DataTable().ajax.reload();

                        limpiar(); //Llamada a la funcion limpiar del archiv0
                    }
        });//Fin de la validacion
    }else{
        //mostrar el mensaje de error
        bootbox.alert("No coincide la contraseña");
    }
}//Fin de la funcion guardaryeditar


/**
 * Funcion que cambia el estado del usuario con el id enviado por ajax
 * IMPORTANTE: id_usuario,est se envian por POST via AJAX
 * **/
function cambiarEstado(id_usuario,est){

    bootbox.confirm(
        "¿Está seguro de cambiar el estado?",
        function(result){

            if(result){
                $.ajax({
                    url:"../ajax/usuario.php?op=activarydesactivar",
                    method:"POST",
                    data:{ id_usuario: id_usuario, est:est }, //Toma el valor del id y estado
                    success: function(data){
                                $('#usuario_data').DataTable().ajax.reload();
                            }//Fin de funcion 
                })
            }//Fin de la estructura if
        }//Fin de la funcion
    );//Fin del bootbox

}//Fin de la funcion cambiarEstado

init();