<script type="text/css">
            $(".alert").alert()
        </script>
<!--Inicio formulario Cliente-->
<div class="container">
 <form class="well" name="formciente" action="<?php print base_url();?>index.php/clientes/<?php print $accion;?>" method="post">
                <table cellpadding="4" >	
                    <div class="alert alert-info">Aviso: Todos los datos que ingrese serán guardados en mayúsculas sin importar como los digite
                        <button data-dismiss="alert" class="close" type="button">x</button></div>
                    <tr><td>
                            <label>Profesión:</label>     
                            <select name="profesion">                                
                            <option>MR.</option>
                            <option>MS.</option>
                            <option>SR..</option>
                            <option>SRA.</option>                            
                            <option>SRTA.</option>
                            <option>ING.</option>
                            <option>ARQ.</option>
                            <option>LIC.</option>
                            <option>TLGO.</option>
                            <option>TEC.</option>
                            <option>MEC.</option>
                            </select>                                                                          
                        </td><td>
                            <label>Nombres:</label>							
                            <input type="hidden" name="id" value="<?php print set_value('id'); @print $Id_Cliente;?>">
                            <input type="text" class="span3" placeholder="Nombres" maxlength="50" name="nombres" required="required" value="<?php print set_value('nombres'); @print $Nombres;?>">
                        </td><td>
                            <label>Apellidos:</label>													
                            <input type="text" class="span3" placeholder="Apellidos" maxlength="50" name="apellidos" required="required" value="<?php print set_value('apellidos');@print $Apellidos;?>">
                        </td><td>
                            <label>Numero de Cédula:</label>							
                            <input type="text" class="span3" placeholder="Cédula" maxlength="10" name="cedula" required="required" whith value="<?php print set_value('cedula');@print $Cedula;?>">
                        </td></tr><tr><td>
                            <label>Empresa:</label>
                            <input type="text" class="span3" placeholder="Empresa" maxlength="50" name="empresa" required="required" value="<?php print set_value('empresa');@print $Empresa;?>">
                        </td><td>
                            <label>País:</label>
                            <input type="text" class="span3" placeholder="País" maxlength="50" name="pais" required="required" value="<?php print set_value('pais');@print $Pais;?>">
                        </td>
                    </tr><tr><td colspan="3">
                            <label>Notas:</label>
                            <textarea class="input-xlarge" id="textarea" rows="4" placeholder="Notas Importantes de este Cliente" name="notas"><?php print set_value('notas'); @print $Notas;?></textarea>
                        </td><td>                        
                            <button type="submit" class="btn btn-info pull-right"><i class="icon-folder-close icon-white"></i>Guardar</button>
                        </td></tr>
            </form>
        </table>
</div>
<!--Fin formulario Cliente-->