<div class="alert alert-info">
<h4 class="spam">Información <?php print $tipo; ?> <span class="label label-info"><?php @print $nombre; ?></span><?php  @print $enlace; ?></h4>

</div>
<div class="hero-unit">
<table class="table table-condensed table-striped ">	
<tbody>
	<?php 
	foreach ($detalles as $key => $valor ) {		
		print '<tr><td><b>' . $key . '</b><td>';
		print '<td>' . $valor . '<td></tr>';		
	}
	?>
</tbody>
</table>
<?php
	if ($accion == 'presentar'){
		if ($tipo == 'Cliente'){
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/actualizar/'. $id . '" class="btn">Editar</a>';		
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/certificaciones/'. $id . '" class="btn">Ver Certificados</a>';		
			print '&nbsp;<a href="' . base_url() . 'index.php/certificaciones/crear/'. $id . '" class="btn">Asignar un Certificado</a>';
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/eliminar/'. $id . '" class="btn btn-danger">Eliminar</a>';		
		}elseif($tipo == 'Certificado'){
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/actualizar/'. $id . '" class="btn">Editar</a>';							
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/eliminar/'. $id . '" class="btn btn-danger">Eliminar</a>';
		}elseif ($tipo == 'certificacion') {
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/actualizar/'. $id . '" class="btn">Editar</a>';							
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/eliminar/'. $id . '" class="btn btn-danger">Eliminar</a>';		}
	}elseif ($accion == 'eliminar'){
		if ($tipo == 'Cliente'){		
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/confirmar/'. $id . '" class="btn btn-danger">Eliminar Este Cliente</a>';		
		}elseif($tipo == 'Certificado'){			
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/confirmar/'. $id . '" class="btn btn-danger">Eliminar Este Certificado</a>';
		}elseif ($tipo == 'certificacion') {
			print '&nbsp;<a href="' . base_url() . 'index.php/' . $controlador . '/confirmar/'. $id . '" class="btn btn-danger">Eliminar</a>';							
			
	}
	}	
	?>
</div>
</div>