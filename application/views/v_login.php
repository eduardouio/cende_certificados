<div class="container">
<!--Inicio del cuerpo de Login-->
	<?php @print $error;?>
<h1 align="center" class="label label-info">Bienvenido(a)... <br/> 
  Para Ingresar al <b>Panel de Administracion de Cende </b> por favor Identifíquese</h1>
<!--inicio formulario de identificacion-->
<div class="well well-small" align="center"> 
<form class="form" align="center" action="<?php print base_url();?>index.php/login/identificar" method="post">  
      <span>Usuario</span> 
      <input type="text" lpaceholder="Usuario" name="usuario" required="required">
      <span>Contraseña</span> 
      <input type="password" placeholder="Contraseña" name="pass" required="required">
      <br/>
      <button type="submit" class="btn btn-info">Ingresar</button>        
</form>      
</div>
  <!--fin formulario de identificacion-->
  <!--Fin del cuerpo de Login-->