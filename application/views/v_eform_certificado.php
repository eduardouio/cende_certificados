<!--<form certificado>-->
<script type="text/css">
            $(".alert").alert()
        </script>
<div class="container">
<form class="well" name="eliminarcertificado" action="<?php print base_url();?>index.php/certificados/<?php print $accion;?>" method="post">
                <div class="alert alert-info">Aviso: Todos los datos que ingrese serán guardados en mayúsculas sin importar como los digite
                    <button data-dismiss="alert" class="close" type="button">x</button>
                </div>
                <input type="hidden" name="id" value="<?php print set_value('id'); @print $Id_Certificado;?>">
                <label>Código:</label>
                <input type="text" class="span3" placeholder="Código" maxlength="10" name="codigo" required="required" value="<?php print set_value('codigo'); @print $Codigo;?>">
                <label>Método:</label>
                <input type="text" class="span3 input-large" placeholder="Método" maxlength="100" name="metodo"  required="required" value="<?php print set_value('metodo'); @print $Metodo;?>"> 
                <button type="submit" class="btn btn-info pull-right"><i class="icon-folder-close icon-white"></i>Guardar</button>
            </form>
</div>
<!--</form certificado>-->