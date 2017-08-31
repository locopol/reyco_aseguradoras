<?php $this->load->view('header'); ?>
<?php if(!$this->session->userdata('isLoggedIn')) redirect(base_url() . 'login'); ?>
<body>

<!-- Preloader -->
<div id="preloader">
	<div id="status">&nbsp;</div>
</div>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <a class="prev"><</a>
    <a class="next">></a>
    <a class="close">x</a>
    <ol class="indicator"></ol>
</div>
<div class="bz">
  <div id="container">
    <div id="header"><img width="982" src="<?php echo base_url();?>images/logo_.jpg" alt="Remates Reyco" title="Remates Reyco" /></div>

<!-- menu principal -->
<?php $this->load->view('menu'); ?>
<!-- fin menu -->


        <?php if ($login) { ?>
         	<div class="ui-widget" style="padding-top: 2px">
	 	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		<p style="font-size: 12px; font-weight: bold;"><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<?php echo $login;?></p>
	 	</div>
	 	</div>
	<?php } ?>

        <?php if ($error) { ?>
         	<div class="ui-widget" style="padding-top: 2px">
	 	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		<p style="font-size: 12px; font-weight: bold;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		<?php echo $error;?></p>
	 	</div>
	 	</div>
	<?php } ?>
	<?php if ($success) { ?>
	<div class="ui-widget" style="padding-top: 2px">
	<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
	    <p style="font-size: 12px; font-weight: bold;"><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<?php echo $success;?></p>
	 </div>
	 </div>
	<?php } ?>
<div id="accordion">

<?php if ($historial) { ?>
<h4 style="font-size: 15px; font-weight: bold;">Antecedentes del vehiculo

<?php if ((($estado == 'S' || $estado == 'F') && $feedback == 0) && $historial['estado']!="Vendido") { ?>
<button id="button_edit" style="margin-top: -.25em; margin-right: 1.7em;" onclick="dialog.dialog('open');">
	<i class="ui-icon ui-icon-mail-open" style="float:left; margin-top: 0.2em; margin-left: -0.5em"></i><span style="font-size: 12px; float: left; margin-left: 0.5em;margin-bottom: 0.4em">Solicitar modificaci&oacute;n de datos</span>
</button>
<?php } elseif ((($estado == 'S' || $estado == 'F') && $feedback == 1) && $historial['estado']!="Vendido") { ?>
<button id="button_edit" style="margin-top: -.25em; margin-right: 1.7em;" disabled>
	<i class="ui-icon ui-icon-mail-open" style="float:left; margin-top: 0.2em; margin-left: -0.5em"></i><span style="font-size: 12px; float: left; margin-left: 0.5em;margin-bottom: 0.4em">Solicitud de modificaci&oacute;n de datos enviada</span>
</button>

<?php } ?>

</h4>

<div>
<?php $this->load->view('historial'); ?>

<?php if ((($estado == 'S' || $estado == 'F') && $feedback == 0) && $historial['estado']!="Vendido") { ?>
  <!-- aqui va la solicitud de datos -->
      <div id="dialog" title="Solicitud de modificaci&oacute;n de informaci&oacute;n">
	<br />
	<fieldset style="border-radius: 5px">
	  <legend>Ingrese los datos requeridos a modificar</legend>
		<?php echo form_open('update_send/' . $historial['id'], array('id' => 'update_send')); ?>
		<input type="hidden" name="idInventario" value="<?php echo $historial['inventario']; ?>">
		<table border=0 style="padding: 0px">
   		<tr>
		<td width="100%">Valor Indemnizado ($): </td><td><input class="decnumber" name="val_monto_indemnizado" value="<?php if ($historial['montoindem'] == 0) echo '';else echo $historial['montoindem']; ?>"></td>
		</tr><tr>
		<td>Valor Minimo ($): </td><td> <input class="decnumber" name="val_monto_minimo" value="<?php if ($historial['montomin'] == 0) echo '';else echo $historial['montomin']; ?>"></td>
		</tr><tr>
		 <td>Incluir en proximo remate: </td><td> <input type="checkbox" name="val_prox_remate"></td>
		</tr>
		</table>
		</form>
	</fieldset>
    </div>

<?php } ?>
</div>

<?php } else { ?>

<h4 style="font-size: 15px; font-weight: bold;"><?php switch ($estado) { case 'V': echo 'Historial total de vehiculos'; break; case 'S': echo 'Vehiculos disponibles en Stock'; break; } ?></h4>
	<div>

	<?php $query=$this->dbmaint->get_lista($estado,$this->session->userdata('company')); echo str_replace("\r", '',str_replace("\n", '',$this->table->generate($query))); ?><br />
</div>
<?php } ?>

</div>
<?php $this->load->view('footer'); ?>
</div>
</div>


<script type="text/javascript">
// Preloader 
	//<![CDATA[
	$(window).load(function() { // makes sure the whole site is loaded
//$(document).ready(function() {
			$("#status").fadeOut(); // will first fade out the loading animation
			$("#preloader").delay(300).fadeOut("fast"); // will fade out the white DIV that covers the website.
		})
	//]]>
</script> 
</body>
</html>
