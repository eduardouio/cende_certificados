<!-- Inicio del cuerpo -->
  <body>
    <div class="container">
    <!-- Inicio de la barra de menu -->
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand"><?php print $titulo;?></a>
          <div class="nav-collapse collapse">
            <ul class="nav">             
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">&nbsp;&nbsp;<b>Clientes</b>&nbsp;&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php print base_url();?>index.php/clientes"><i class="icon-th icon-black"></i>&nbsp;Explorar Clientes</a></li>
                  <li><a href="<?php print base_url();?>index.php/clientes/crear"><i class="icon-file icon-black"></i>&nbsp;Nuevo(a) Cliente</a></li>                  
                </ul>
              </li>              
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">&nbsp;&nbsp;<b>Certificados</b>&nbsp;&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php print base_url();?>index.php/certificados"><i class="icon-th icon-black"></i>&nbsp;Explorar Certificados</a></li>
                  <li><a href="<?php print base_url();?>index.php/certificados/crear"><i class="icon-file icon-black"></i>&nbsp;Nuevo Certificado</a></li>                  
                </ul>
              </li>
            </ul>            
          </div><!--/.nav-collapse -->
          <a href="<?php print base_url();?>index.php/login/salir" class="btn btn-danger navbar-form pull-right">&nbsp;<i class="icon-off icon-black"></i>&nbsp;</a>
        </div>
      </div>
    </div>
<!-- Fin de la barra de menu -->