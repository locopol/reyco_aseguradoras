<?php $this->load->view('header'); ?>
<?php if(!$this->session->userdata('isLoggedIn')) redirect(base_url() . 'login'); ?>

<body>

<!-- Preloader -->
<div id="preloader">
	<div id="status">&nbsp;</div>
</div>

<div class="bz">
  <div id="container">
    <div id="header"><img width="982" src="<?php echo base_url();?>images/logo_.jpg" alt="Remates Reyco" title="Remates Reyco" /></div>
<!-- menu principal -->
<?php 

$this->load->view('menu'); ?>
<!-- fin menu -->


        <?php if ($login) { ?>
         	<div class="ui-widget" style="padding-top: 2px">
	 	<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
		<p style="font-size: 12px; font-weight: bold;"><span class="ui-icon ui-icon-person" style="float: left; margin-right: .3em;"></span>
		<?php echo $login;?></p>
	 	</div>
	 	</div>
	<?php } ?>

        <?php if ($error) { ?>
         	<div class="ui-widget" style="padding-top: 2px">
	 	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		<p style="font-weight: bolder;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		<?php echo $error;?></p>
	 	</div>
	 	</div>
	<?php } ?>

	<?php if ($success) { ?>
	<div class="ui-widget" style="padding-top: 2px">
	<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
	    <p style="font-weight: bolder;"><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<?php echo $success;?>
	
		
	    </p>


	 </div>
	 </div>
	<?php } ?>


<div id="accordion">



<?php if($historial) { ?>

<h4 style="font-size: 15px; font-weight: bold;">Antecedentes del Vehiculo

<?php if ($historial['estado'] == 'Stock' && $feedback == 0) { ?>
<button id="button_edit" style="margin-top: -.25em; margin-right: 1.7em;" onclick="dialog.dialog('open');">
	<i class="ui-icon ui-icon-mail-open" style="float:left; margin-top: 0.2em; margin-left: -0.5em"></i><span style="font-size: 12px; float: left; margin-left: 0.5em;margin-bottom: 0.4em">Solicitar modificaci&oacute;n de datos</span>
</button>
<?php } elseif ($historial['estado'] == 'Stock' && $feedback == 1) { ?>
<button id="button_edit" style="margin-top: -.25em; margin-right: 1.7em;" disabled>
	<i class="ui-icon ui-icon-mail-open" style="float:left; margin-top: 0.2em; margin-left: -0.5em"></i><span style="font-size: 12px; float: left; margin-left: 0.5em;margin-bottom: 0.4em">Solicitud de modificaci&oacute;n de datos enviada</span>
</button>


<?php } ?>

</h4>

	<div>

<?php $this->load->view('historial'); ?>

<div>

<?php if ($historial['estado'] == 'Stock') { ?>
  <!-- aqui va la solicitud de datos -->
      <div id="dialog" title="Solicitud de modificaci&oacute;n de informaci&oacute;n">
	<br />
	<fieldset style="border-radius: 5px">
	  <legend>Ingrese los datos requeridos a modificar</legend>
		<?php echo form_open('update_send/' . $historial['id'], array('id' => 'update_send')); ?>
		<input type="hidden" name="idInventario" value="<?php echo $historial['inventario']; ?>">
		<table border=0 style="padding: 0px">
   		<tr>
		<td width="100%">Valor Indemnizado ($): </td><td><input class="decnumber" name="val_monto_indemnizado" value="<?php echo $historial['montoindem']; ?>"></td>
		</tr><tr>
		<td>Valor Minimo ($): </td><td> <input class="decnumber" name="val_monto_minimo" value="<?php echo $historial['montomin']; ?>"></td>
		</tr><tr>
		 <td>Incluir en proximo remate: </td><td> <input type="checkbox" name="val_prox_remate"></td>
		</tr>
		</table>
		</form>
	</fieldset>
    </div>

<?php } ?>
</div>

	</div>

<?php } elseif($result) { ?>


<h4 style="font-size: 15px; font-weight: bold;">Resultados de la busqueda</h4>
	<div>

<?php echo $this->table->generate($result); ?><br />

</div>
	<?php } else { ?>

	 <h4 style="font-size: 15px; font-weight: bold;">Criterios de busqueda</h4> 

	<div>
<?php echo form_open('search'); ?>
<table border=0>
 <tr>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

<td>
  <fieldset style="border-radius: 5px">
    <legend>Seleccione la opcion de busqueda: </legend>
    <label for="radio-1">Por Siniestro</label>
    <input class="checkbox" type="radio" name="base" id="radio-1" value="sin" checked="checked">
    <label for="radio-2">Por Patente&nbsp;</label>
    <input class="checkbox" type="radio" name="base" id="radio-2" value="pat">
<!--    <label for="radio-3">N° Liquidaci&oacute;n</label>
    <input class="checkbox" type="radio" name="base" id="radio-3" value="liq">-->
  </fieldset>
</td>


<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  <td>
	<fieldset style="border-radius: 5px">
        <legend>Ingrese criterio de busqueda:</legend>
	<input name="pattern" type="text" size="30">
	
       </fieldset>
</td>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  <td><input id="button" type="submit" value="Buscar Items"></td>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
 </tr>
</table>
</form>
	</div>
<?php } ?>
</div>
<?php $this->load->view('footer'); ?>
</div>

<!-- Preloader -->
<script type="text/javascript">
	//<![CDATA[
		$(window).on('load', function() { // makes sure the whole site is loaded
			$("#status").fadeOut(); // will first fade out the loading animation
			$("#preloader").delay(350).fadeOut("slow"); // will fade out the white DIV that covers the website.
		})
	//]]>
</script> 


</body>
</html>

