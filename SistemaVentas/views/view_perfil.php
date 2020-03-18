<div id="resultados_ajax" class="text-center"></div>


 <!--FORMULARIO PERFIL USUARIO MODAL-->

<div id="perfilModal" class="modal fade">
  <div class="modal-dialog">
    <form action="" class="form-horizontal" method="post" id="perfil_form">
      <div class="modal-content">
      
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar Perfil</h4>
        </div>
        <div class="modal-body">


               <div class="form-group">
                  <label for="inputText3" class="col-lg-1 control-label">Cédula</label>

                  <div class="col-lg-9 col-lg-offset-1">
                    <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Cédula" required pattern="[0-9]{0,15}">
                  </div>
              </div>

              <div class="form-group">
                  <label for="inputText1" class="col-lg-1 control-label">Nombres</label>

                  <div class="col-lg-9 col-lg-offset-1">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombres" required pattern="^[a-zA-Z_áéíóúñ\s]{0,30}$">
                  </div>
              </div>

                <div class="form-group">
                  <label for="inputText1" class="col-lg-1 control-label">Apellidos</label>

                  <div class="col-lg-9 col-lg-offset-1">
                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellidos" required pattern="^[a-zA-Z_áéíóúñ\s]{0,30}$">
                  </div>
              </div>

              

               <div class="form-group">
                  <label for="inputText1" class="col-lg-1 control-label">Nombre de usuario</label>

                  <div class="col-lg-9 col-lg-offset-1">
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Nombres" required pattern="^[a-zA-Z_áéíóúñ\s]{0,30}$">
                  </div>
              </div>


               <div class="form-group">
                  <label for="inputText3" class="col-lg-1 control-label">Contraseña</label>

                  <div class="col-lg-9 col-lg-offset-1">
                    <input type="password" class="form-control" id="password1" name="password1" placeholder="Password" required>
                  </div>
              </div>

               
               <div class="form-group">
                  <label for="inputText3" class="col-lg-1 control-label">Confirmar contraseña</label>

                  <div class="col-lg-9 col-lg-offset-1">
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="Repita Password" required>
                  </div>
              </div>



               <div class="form-group">
                  <label for="inputText4" class="col-lg-1 control-label">Teléfono</label>

                  <div class="col-lg-9 col-lg-offset-1">
                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" required pattern="[0-9]{0,15}">
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputText4" class="col-lg-1 control-label">Correo</label>

                  <div class="col-lg-9 col-lg-offset-1">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Correo" required="required">
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputText5" class="col-lg-1 control-label">Dirección</label>
                 
                 <div class="col-lg-9 col-lg-offset-1">
                 <textarea class="form-control  col-lg-5" rows="3" id="direccion" name="direccion"  placeholder="Direccion ..." required pattern="^[a-zA-Z0-9_áéíóúñ°\s]{0,200}$"></textarea>
                 </div>
                 
                </div>



          
          </div>
                 <!--modal-body-->

        <div class="modal-footer">
        <input type="hidden" name="id_usuario" id="id_usuario"/>
          <!--<input type="hidden" name="operation" id="operation"/>-->

          <button type="submit" name="action" id="" class="btn btn-success pull-left" value="Add"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar </button>

          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cerrar</button>
        </div>
      </div>
    </form>
  </div>
</div>


 <!--FIN FORMULARIO PERFIL USUARIO MODAL-->
 <script src="../public/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="../controllers/cont_perfil.js"></script> 


