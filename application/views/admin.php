<?php $this->load->view('header'); ?>
<?php if(!$this->session->userdata('isLoggedIn')) redirect(site_url('login')); ?>

<body>

<!-- Preloader -->
<div id="preloader">
	<div id="status">&nbsp;</div>
</div>

<div class="bz">
  <div id="container">
    <div id="header"><img width="982" src="<?php echo site_url('images/logo_.jpg'); ?>" alt="Remates Reyco" title="Remates Reyco" /></div>
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

<?php switch ($estado) {
	case 'addcp':

?>

<h4 style="font-size: 15px; font-weight: bold;">Agregar nuevo acceso a compa&ntilde;ia</h4>
	<div>

<fieldset style="border-radius: 5px">
 <legend style="font-size: 14px; font-weight: bold">Informaci&oacute;n de credencial de acceso</legend>
	<?php echo form_open(site_url('base/usrmaint/add')); ?>
	<input type="hidden" id="accion" name="accion" value="do_add">
	
	<table border=0 class="historial_table" >
	 <tr>
	 	<td>ID usuario:</td><td> <input name="cp_user" type="text" ></td> 
	 </tr>
	 <tr>
	 	<td>Titulo de la cuenta:</td><td> <input name="cp_title" type="text" autocomplete="off" ></td>
	 </tr>
	 <tr>
	  	<td>Contrase&ntilde;a:</td><td> <input id="cp_passwd" name="cp_passwd" type="password" autocomplete="off"></td>
         </tr>
         <tr>
	  	<td>Compa&ntilde;ias autorizadas: <br /><p style="font-size: 9px"> (usar CTRL para selecci&oacute;n multiple) </p></td><td> <select multiple name="cp_company[]"> <?php foreach ($this->dbmaint->get_proveedores() as $opcion) { echo '<option>' . $opcion['proveedor'] . '</option>' . "\n\r"; } ?> </select></td>
	 </tr>
	 <tr>
         <tr>
	  	<td> Email de notificaci&oacute;n :</td><td> <input id="cp_email" name="cp_email" type="text"></td>
	 </tr>
	 <tr>
	  	<td> Permitir modificaciones :</td><td> <input type="checkbox" name="cp_update"></td>
	 </tr>
	 <tr>

	  <td> </td>
	   <td><input id="button" type="submit" value="Crear acceso de compa&ntilde;ia"></td>
	 </tr>
	</table>
	</form>
</fieldset>

</div>

<?php 
	break;
	case 'delcp':
?>

<h4 style="font-size: 15px; font-weight: bold;">Eliminar acceso de compa&ntilde;ia</h4>
<div>

	<div>

	<?php $query=$this->dbmaint->get_users_del(); echo str_replace("\r", '',str_replace("\n", '',$this->table->generate($query))); ?><br />
</div>


  <!-- aqui va la confirmaci&oacute;n de eliminaci&oacute;n -->
      <div id="dialog" title="Confirmaci&oacute;n">
	<br />
	<fieldset style="border-radius: 5px">
	  	&iquest;Esta seguro de eliminar la cuenta?
		<?php echo form_open(site_url('base/usrmaint/del'), array('id' => 'delcp_send')); ?>
		<input type="hidden" id="idUser" name="idUser" value="TST">
		</form>
	</fieldset>
    </div>


</div>

<?php 
	break;
	case 'pwdcp':
?>

<h4 style="font-size: 15px; font-weight: bold;">Cambiar contrase&ntilde;a de acceso</h4>
<div>

	<div>

	<?php $query=$this->dbmaint->get_users_pwd(); echo str_replace("\r", '',str_replace("\n", '',$this->table->generate($query))); ?><br />
</div>


  <!-- aqui va la confirmaci&oacute;n de cambio de clave -->
      <div id="dialog" title="Modificaci&oacute;n de contrase&ntilde;a">
	<br />
	<fieldset style="border-radius: 5px">
		<?php echo form_open(site_url('base/usrmaint/pwd'), array('id' => 'pwdcp_send')); ?>
		<input type="hidden" id="idUser" name="idUser" value="TST">
		<table border=0 style="padding: 0px">
   		<tr>
		<td width="100%">Nueva contrase&ntilde;a: </td><td><input type="password" name="pwd1" value=""></td>
		</tr><tr>
		<td>Reingrese contrase&ntilde;a: </td><td> <input type="password" name="pwd2" value=""></td>
		</tr>
		</table>
		</form>
	</fieldset>
    </div>


</div>

<?php 
		break;
}
?>
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

