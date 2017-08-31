<?php $this->load->view('header');?>
<body>

<!-- Preloader -->
<div id="preloader">
	<div id="status">&nbsp;</div>
</div>

<div class="bz">
  <div id="container">
  <div id="header">
<img width="982" src="<?php echo base_url(); ?>images/logo_.jpg" alt="Remates Reyco" title="Remates Reyco" />
</div>

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
	    <p style="font-weight: bolder;"><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<?php echo $success;?>	
	    </p>
	 </div>
	 </div>
	<?php } ?>

<div id="accordion">
<h4 style="font-size: 15px; font-weight: bold;">Sistema de informaci&oacuten de aseguradoras - Inicio de sesi&oacute;n</h4>
<div>

<?php echo form_open(base_url() . 'login/login_user'); ?>
<fieldset class="fieldset">
    <legend style="font-weight: bold">Credenciales de Usuario</legend>
    <ol>
        <li>
            <label for="login">Nombre de usuario:</label>
            <?php
            echo form_input(array(
                'name' => 'user',
                'id' => 'user',
                'maxlength' => '20',
                'size' => '20',
                    ), set_value('user'));
            ?>
        </li>
        <li>
            <label for="password">Contrase&ntilde;a: </label>
            <?php
                echo form_password(array(
                    'name' => 'password',
                    'id' => 'password',
                    'maxlength' => '20',
                    'size' => '20',
                ));
            ?>
        </li>
        <li>
            <label for="login">&nbsp;</label>
            <?php
                echo form_submit(array(
                    'name' => 'login',
                    'id' => 'button',
                    'value' => 'Ingresar al sistema',
                    'class' => 'login'
                ));
            ?>
        </li>
    </ol>
</fieldset>
<?php echo form_close(); ?>
</div>
</div>
<?php $this->load->view('footer'); ?>
</div>
		</div>
<!-- Preloader -->
<script type="text/javascript">
	//<![CDATA[
		$(window).on('load', function() { // makes sure the whole site is loaded
			$("#status").fadeOut(); // will first fade out the loading animation
			$("#preloader").delay(300).fadeOut("slow"); // will fade out the white DIV that covers the website.
		})
	//]]>
</script> 
</body>
</html>
