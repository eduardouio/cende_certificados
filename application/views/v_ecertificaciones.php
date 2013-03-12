<div class="alert alert-info">
<h4 class="spam">Listado de Certificaciones <span class="label label-info"><?php @print $nombre; ?></span>&nbsp;<a href="<?php print base_url();?>index.php/clientes/presentar/<?php print $id_cliente;?>"><?php print $Cedula;?></a></h4>

</div>

<div>
  <a class="btn btn-inverse" href="<?php print base_url();?>index.php/certificaciones/crear/<?php print $id_cliente;?>"><i class="icon-file icon-white"></i>&nbsp;Nueva Certificación</a></li>
</div>                
<!-- Inicio Tabla de informacion -->
<div class="tabla de informacion">
  <table class="table table-bordered table-hover table-condensed">
    <thead>
      <tr>
      <?php      
        foreach ($columnas as $row) {                    
          print '<th>' . $row . '</th>';
        }
      ?>                  
      <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <!-- inicio del contenido de la fila de una tabla-->
      <tr>
         <?php
         /**
         * Vista encargada de mostrar los datos y armar los enlaces alas respectivas
         * paginas.
         *
         * @var id_certificacion parametro del metodo de presentacion de la clase certificacion
         * @var id_certificado parametro del metodo de presentacion de la clase certificados 
         */
          $id_certificacion = 0;
          $id_certificado = 0;
          //recorremos los resultados de la consulta                  
          foreach ($query->result() as $row) {                    
            print '<tr>';                    
            //bandera para los links
            $i = 0;
            //recorremos la fila                    
            foreach ($row as $item) {
              //realizamos seleccion para el caso de generar links
              switch ($i) {
                case 0:
                    $id_certificacion = $item;
                    print ('<td>' . $item . '</td>');
                  break;
                  case 1:
                    print ('<td><a href="'. base_url() .'index.php/certificaciones/presentar/' . $id_certificacion . '">' . $item . '</a></td>');  
                  break;
                  case 2:                    
                    print ('<td>' . $item . '</td>');
                  break;
                  case 3:
                  $id_certificado = $item;                   
                  break;
                  case 4:
                    print ('<td><a href="'. base_url() .'index.php/certificados/presentar/' . $id_certificado . '">' . $item . '</a></td>');                    
                    break;
                case 7:           
                    $hoy = date('Y-m-d');
                    if ($item > $hoy){
                        print ('<td>' . $item . '</td>');
                    }else{                      
                        print ('<td class="alert">' . $item . '</td>');
                    }
                    break;
                default:
                print ('<td>' . $item . '</td>');
                  break;
              }

              $i = $i +1;
            }
            print  '<td> <span class="dropdown"/>
            <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <i class="icon-tasks icon-white"></i> <b class="caret"></b></a>
            <ul class="dropdown-menu">';                             
            //eleccion de menu en la tabla de datos                                  
            print '<li><a href="'. base_url() .'index.php/certificaciones/presentar/'. $id_certificacion .'"><i class="icon-user icon-black"></i>&nbsp;Ver Todo</a></li>';
            print '<li><a href="'. base_url() .'index.php/certificaciones/actualizar/'. $id_certificacion .'"><i class="icon-edit icon-black"></i>&nbsp;Editar</a></li>';                        
            print '<li><a href="'. base_url() .'index.php/certificaciones/eliminar/'. $id_certificacion .'"><i class="icon-trash icon-black"></i>&nbsp;Eliminar Certificación</a></li>';
          }
            print '</ul></td></tr>';                    
          ?>                                 
      </tr>                 
      <!-- Fin del cntenido de las filas de la Tabla-->
      </tbody>
  </table>
</div>        
<!-- Fin tabla de informacion-->