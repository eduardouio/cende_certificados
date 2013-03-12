	<div class="row">
    <div class="span10">	
    <form class="form-search" action="<?php print base_url()?>index.php/<?php print $controlador ?>/buscar" method="post">
    <input type="text" name ="criterio" class="input-medium search-query" placeholder="Ingrese criterio...">
    <select name="columna">                
        
        <?php            
        if ($controlador == 'clientes') {
            print '<option>Apellidos</option>';
        }
        foreach ($columnas as $key => $value) {
            print '<option>';
            print $value;
            print '</option>';
        }
        ?>
    </select>
    <button type="submit" class="btn">Buscar</button>
    </form>
    </div>
    <div class="span2">
    	<a href="<?php print base_url()?>index.php/<?php print $controlador;?>" class="btn btn-inverse"><i class="icon-white icon-refresh"></i>&nbsp; &nbsp; Listar Todos &nbsp; &nbsp;</a>
    </div>
    </div>