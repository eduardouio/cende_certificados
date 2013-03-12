    <script type="text/javascript">
    $(function() {
        $( "#fecha_inicio" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true, 
            showAnim: "clip",
            dateFormat: "yy-mm-dd",            
            numberOfMonths: 3,
            onSelect: function( selectedDate ) {
                $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#fecha_fin" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,             
            showAnim: "clip",
            dateFormat: "yy-mm-dd",
            numberOfMonths: 3,
            onSelect: function( selectedDate ) {
                $( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate);
            }
        });
    });    
    </script>
    
<!--<form certificacion>-->
<script type="text/javascript">
            $(".alert").alert()
</script>
<div class="alert alert-info">
<h4 class="spam"> Certificación de Cliente: <span class="label label-info"><?php @print $nombres; ?></span></h4>

</div>
<div class="container">    
 <form class="well" action="<?php print base_url();?>index.php/certificaciones/<?php print $accion;?>" method="post">
                <table cellpadding="4" >    
                    <div class="alert alert-info">Aviso: Todos los datos que ingrese serán guardados en mayúsculas sin importar como los digite
                        <button data-dismiss="alert" class="close" type="button">x</button></div>
                    <tr><td>
                            <input type="hidden" name="id_cc" value="<?php print set_value('id_cc'); @print $Id_cc;?>">
                            <input type="hidden" name="id_cliente" value="<?php print set_value('id_cliente'); @print $Id_Cliente;?>" >
                            <label>Registro:</label>     
                            <input type="text" class="span3" placeholder="CEN-CC-000" maxlength="12" name="id_registro" required="required" value="<?php print set_value('id_registro'); @print $Id_Registro;?>">
                        </td><td>
                            <label>Certificado:</label>                         
                            <select type="text" class="span3" placeholder="Seleccione.." name="certificado">
                                <?php                                
                                    foreach ($certificados as $key => $value) {
                                        print '<option>';
                                            print $value->id_certificado . '-' . $value->certificado;
                                        print '</option>';
                                    }
                                ?>
                            </select>                        
                        </td><td>
                            <label>Edisión:</label>                         
                            <input type="text" class="span3" placeholder="Edision" maxlength="50" name="edision" value="<?php print set_value('edision'); @print $Edision;?>">
                        </td><td>
                            <label>Fecha Certificacion:</label>                                                 
                            <input type="date" class="span3" id="fecha_inicio"  maxlength="25" name="fecha_certificacion" required="required" value="<?php print set_value('fecha_certificacion');@print $Fecha_Certificacion;?>">
                        </td></tr><tr><td>
                            <label>Fecha Vencimiento:</label>                           
                            <input type="date" class="span3" id="fecha_fin"  maxlength="25" name="fecha_vencimiento" required="required" value="<?php print set_value('fecha_vencimiento');@print $Fecha_Vencimiento;?>">
                        </td>                       
                        <td>
                            <label>Examinación General:</label>
                            <input type="text" class="span3" placeholder="0-100" maxlength="3" name="examinacion_general" required="required" value="<?php print set_value('examinacion_general');@print $Examinacion_general;?>">
                        </td><td>
                        <label>Examinacion Específica:</label>
                            <input type="text" class="span3" placeholder="0-100" maxlength="3" name="examinacion_especifica" required="required" value="<?php print set_value('examinacion_especifica');@print $Examinacion_especifica;?>">
                        </td><td>
                        <label>Examinacion Parcial:</label>
                            <input type="text" class="span3" placeholder="0-100" maxlength="3" name="examinacion_parcial" required="required" value="<?php print set_value('examinacion_parcial');@print $Examinacion_parcial;?>">
                        </td></tr><tr><td>                                                
                            <label>Examen:</label>
                            <input type="text" class="span3" placeholder="Estado" maxlength="20" name="examen" required="required" value="<?php print set_value('examen');@print $Estado_Examen;?>">
                        </td><td></td><td></td><td>
                            <button type="submit" class="btn btn-info pull-right"><i class="icon-folder-close icon-white"></i>Guardar</button>
                        </td></tr>
            </form>
        </table>
</div>
<!--</form certificacion>-->