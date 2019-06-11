<?php if(!$this->session->userdata('isLoggedIn')) redirect(base_url() . 'login'); ?>

<fieldset style="border-radius: 5px">
 <legend style="font-size: 14px; font-weight: bold">N° DE INVENTARIO: <?php echo $historial['inventario']; if($historial['estado']=="Vendido") { echo ' / VEHICULO REMATADO'; } else { echo '/ VEHICULO EN STOCK'; } ?><?php if ($historial['proveedor'] == 'ASISTENCIA') echo '/ PROVIENE DE ASISTENCIA'; if ((($estado == 'S' || $estado == 'F') && $feedback == 1) && $historial['estado']!="Vendido") { echo ' / MODIFICACIÓN DE DATOS EN CURSO'; } ?></legend>

<fieldset style="border-radius: 5px">
 <legend>Antecedentes del Item</legend>

<table class="historial_table" width="100%" >
	<tr style="font-size: 12px;">
		<td style="font-size: 14px; font-weight: bold" width="35%">Siniestro: <?php echo $historial['siniestro']; ?></td> 

		<td style="font-size: 14px; font-weight: bold">Fecha Remate: <?php echo $historial['fecharemate']; ?></td>
		<td style="font-size: 12px; font-weight: bold"><?php if ($historial['estado'] == 'Vendido') echo 'Monto adjudicado: $' . number_format($historial['montoadj'],0,',','.'); elseif ($historial['estado'] == 'Stock') echo 'Monto minimo: $' . number_format($historial['montomin'],0,',','.'); ?></td>
	</tr>
	<tr style="font-size: 12px;">
		<td style="font-weight: bold">Fecha recepci&oacute;n Reyco: <?php echo $historial['fecharecep']; ?></td>
		<td style="font-weight: bold">Ubicaci&oacute;n: <?php echo $historial['ubicac']; ?></td>
		<td style="font-weight: bold"><?php if ($historial['estado'] == 'Vendido') echo 'Fecha de liquidaci&oacute;n factura: ' . $historial['fechaliq']; if($historial['estado'] == 'Stock') echo 'Monto indemnizado: $' . number_format($historial['montoindem'],0,',','.'); ?></td>
	</tr>
	<tr style="font-size: 12px;">
		<td style="font-weight: bold">RUT Asegurado: <?php echo $historial['rutduennoant']; ?></td>
		<td style="font-weight: bold">Nombre Asegurado: <?php echo $historial['duennoant']; ?></td>
		<td style="font-weight: bold">Liquidador: <?php echo $historial['liquidador']; ?></td>
	</tr>

</table>
</fieldset>

<fieldset style="border-radius: 5px">
 <legend>Datos del vehiculo</legend>

<table class="historial_table" width="100%" >
 <tr style="font-size: 12px;">
	<td style="font-size: 14px; font-weight: bold" width="35%">Patente: <?php echo $historial['placa']; ?></td> 
	<td>Tipo: <?php echo $historial['tipo']; ?></td>
	<td>A&ntilde;o: <?php echo $historial['anno']; ?></td>
	
 </tr>
 <tr style="font-size: 12px;">
	<td>Marca: <?php echo $historial['marca']; ?></td>
	<td>Modelo: <?php echo $historial['modelo']; ?></td>
	<td>Color: <?php echo $historial['color']; ?></td> 
	
 </tr>
 <tr style="font-size: 12px;">
	<td>VIN / Chasis: <?php echo $historial['chassis']; ?></td>
	<td>Motor: <?php echo $historial['motor']; ?></td>
	<td><b>Lugar Fisico: <?php echo $historial['ubicafisica']; ?></b></td>

 </tr>
</table>
</fieldset>

<fieldset style="border-radius: 5px; width: 450px; float: left;">
 <legend>Comentarios a compa&ntilde;ia</legend>

<textarea rows=2 cols=60><?php echo $historial['comentario']; ?></textarea>

</fieldset>

<fieldset style="border-radius: 5px; width: auto;">
 <legend>Datos adicionales</legend>

<table class="historial_table" width="100%">
	<colgroup>
        <col width="40%" />
        <col width="10%" />
        <col width="30%" />
        <col width="20%" />
    </colgroup>
 <tr style="font-size: 10px;">
	<td>Permiso de circulacion:</td><td><b><?php echo $historial['pcircula']; ?></b></td> 
	<td>Mandato:</td> <td><b><?php echo $historial['mandato']; ?></b></td>
 </tr>
 <tr style="font-size: 10px;">
	<td>Revision tecnica:</b></td><td><b><?php echo $historial['rtecnica']; ?></b></td>
	<td>Llaves:</td><td><b><?php echo $historial['conllave2']; ?></b></td>
 </tr>
</table>


</fieldset>


<?php if($historial['estado']=="Stock") { ?>

<fieldset style="border-radius: 5px; float: left; width: 876px">
<legend>

<div style="display: inline-block; line-height: 1.2;">
 <div style="float:left; padding: 0 5px">
	<?php 
	  if ($this->model_fotos->check_video_path($historial['placa']) == '0')
		  echo 'Fotos disponibles del vehiculo';
	  else
		  echo 'Fotos y video disponibles del vehiculo';

        ?>
 </div>
</div>
</legend>
<div id="links">

<?php  

if ($this->model_fotos->img_path($historial['placa']) == 0) {
	echo '<center><button id="button_fotos" style="margin-top: -.25em; margin-right: 1.7em;"><i class="ui-icon ui-icon-mail-open" style="float:left; margin-top: 0.2em; margin-left: -0.5em"></i><span style="font-size: 12px; float: left; margin-left: 0.5em;margin-bottom: 0.4em">Presione aqui para actualizar fotos</span></center>';

} else {

$foto = $this->model_fotos->img_path($historial['placa']);

for($x=0;$x<6;$x++) {

	// Pegar video a la cola de la lista
	if ($x == 5 && $this->model_fotos->check_video_path($historial['placa']) != '0') {
		echo ' <div style="float: left; border:1px solid #000000; padding-top: 0.2em">' . "\n";
		echo '  <a href="'. base_url() . $this->model_fotos->check_video_path($historial['placa']) . '" type="' . get_mime_by_extension($this->model_fotos->check_video_path($historial['placa'])) . '" data-poster="' . base_url() . 'images/car-shadow-poster.jpg">' . "\n";
		echo '   <img width="144" height="40%" src="'. base_url() . 'images/video-play.jpg" alt="Presione aqu&iacute; para ver video">' . "\n";
		echo ' </a>';
		echo '</div>' . "\n";
	} else {

	// Pegar fotos independiente del video
	if(count($this->model_fotos->img_path($historial['placa'])) > ($x) && $this->model_fotos->img_path($historial['placa']) != 0) {
		echo ' <div style="float: left; border:1px solid #000000; padding-top: 0.2em">' . "\n";
		echo '  <a href="'. base_url() . $foto[$x] . '" type="' . get_mime_by_extension($foto[$x]) . '">' . "\n";
		echo '   <img width="144" height="40%" src="'. base_url() . $foto[$x] . '">' . "\n";
		echo ' </a>';
		echo '</div>' . "\n";
	} else {
	
		 echo ' <div id="' . $x . '" style="float: left; border:1px solid #000000; padding-top: 0.2em" >' . "\n";
		 echo '  <img width="144" height="50%" src="'. base_url() . 'images/noimage.jpg">' . "\n";
		 echo ' </div>' . "\n";
	 }

	}

}

}

?>
</div>
</fieldset>

<script>
// Blueimp gallery
document.getElementById('links').onclick = function (event) {
 event = event || window.event;
 var target  = event.target || event.srcElement,
     link    = target.src ? target.parentNode : target,
     options = {index: link, event: event},
     links   = this.getElementsByTagName('a');
 blueimp.Gallery(links, options);
};

</script>

<?php } ?>
