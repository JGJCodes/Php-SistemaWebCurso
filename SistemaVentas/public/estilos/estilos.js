/*Archivo contenedor de estructuras de estilos propios*/
  
//PRODUCTO
$(":file").filestyle({input: false, buttonText: "Agregar Imagen",buttonName: "btn-primary"});

//CAMPO FECHA - DATEPICKER - PRODUCTOS-->
      $('#datepicker').datepicker({
      /*dateFormat: 'dd-mm-yy',
      autoclose: true*/
       format: "dd/mm/yyyy",
        /*clearBtn: true,
        language: "es",*/
        autoclose: true,
        /*keyboardNavigation: false,
        todayHighlight: true*/
    })

    $('#datepicker2').datepicker({
        /*dateFormat: 'dd-mm-yy',
        autoclose: true*/
         format: "dd/mm/yyyy",
          /*clearBtn: true,
          language: "es",*/
          autoclose: true,
          /*keyboardNavigation: false,
          todayHighlight: true*/
      })

