<!-- Inicio Tabla de informacion -->                
<div class="tabla de informacion">
  <table class="table table-bordered table-hover table-condensed">
    <thead>
      <tr>
      <?php      
      foreach ($columnas as $row) {                    
      print '<th>' . $row . '</th>';
      }?>                  
      <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <!-- inicio del contenido de la fila de una tabla-->
      <tr>
         <?php
          $id = 0;
          //recorremos los resultados de la consulta                  
          foreach ($query->result() as $row) {                    
              print '<tr>';                    
              //bandera para los links
              $i = 0;
              //recorremos la fila                    
              foreach ($row as $item) {
                  //recuperar el id del cliente                                                                   
                  if ($i == 0){
                  $id = $item;
                  }
                  //generar el link en el campo deseado
                  if ($i == 1){
                  print ('<td><a href="'. base_url() .'index.php/'. $controlador .'/presentar/' . $id . '">' . $item . '</a></td>');  
                  }
                  //imprimir el resto de la fila
                  else{
                  print ('<td>' . $item . '</td>');
                  }
                  $i = $i +1;
              }
              print  '<td> <span class="dropdown"/>
              <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <i class="icon-tasks icon-white"></i> <b class="caret"></b></a>
              <ul class="dropdown-menu">';                             
              //eleccion de menu en la tabla de datos                  

              $menu= array();                                    
              switch ($controlador){
                case 'certificados':

                  print  '<li><a href="'. base_url() .'index.php/'. $controlador .'/presentar/'. $id .'"><i class="icon-file icon-black"></i>Ver Certificado</a></li>';                        
                  print  '<li><a href="'. base_url() .'index.php/'. $controlador .'/actualizar/'. $id .'"><i class="icon-edit icon-black"></i>&nbsp;Editar Certificado</a></li>';
                  print  '<li><a href="'. base_url() .'index.php/'. $controlador .'/asignar/'. $id .'"><i class="icon-share-alt icon-black"></i>&nbsp;Asignar Certificado</a></li>';
                  print  '<li><a href="'. base_url() .'index.php/'. $controlador .'/eliminar/'. $id .'"><i class="icon-trash icon-black"></i>&nbsp;Eliminar Todo</a></li>';

                break;
                case 'clientes':                           
                print '<li><a href="'. base_url() .'index.php/'. $controlador .'/presentar/'. $id .'"><i class="icon-user icon-black"></i>&nbsp;Ver Todo</a></li>';
                  print '<li><a href="'. base_url() .'index.php/'. $controlador .'/actualizar/'. $id .'"><i class="icon-edit icon-black"></i>&nbsp;Editar</a></li>';
                  print '<li><a href="'. base_url() .'index.php/'. $controlador .'/certificaciones/'. $id .'"><i class="icon-eye-open icon-black"></i>&nbsp;Ver Certificados</a></li>';
                  print '<li><a href="'. base_url() .'index.php/certificaciones/crear/'. $id .'"><i class="icon-file icon-black"></i>&nbsp;Nuevo Certificado</a></li>';
                  print '<li><a href="'. base_url() .'index.php/'. $controlador .'/eliminar/'. $id .'"><i class="icon-trash icon-black"></i>&nbsp;Eliminar Todo</a></li>';               
              }

          }
             
          print '</ul></td></tr>';                    

          ?>                                 
      </tr>                 
      <!-- Fin del cntenido de las filas de la Tabla-->
      </tbody>
  </table>
</div>        
<!-- Fin tabla de informacion                            
-->