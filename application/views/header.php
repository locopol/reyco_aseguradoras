<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
<meta charset="utf-8">
<title>Sistema de aseguradora de vehiculos</title>
<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet" />
<link href="<?php echo base_url();?>css/blueimp-gallery.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>css/jquery-ui.min.css" rel="stylesheet"/>
<link href="<?php echo base_url();?>css/jqsimplemenu.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>css/private.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script> 
<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>js/jquery.datatables.min.js"></script>
<script src="<?php echo base_url();?>js/blueimp-gallery.min.js"></script>
<script src="<?php echo base_url();?>js/jqsimplemenu.js"></script>
<script src="<?php echo base_url();?>js/jquery.number.min.js"></script>

<script>

var dialog;

$(function() {

	$('.menu').jqsimplemenu(); 
	$( ".selector" ).selectmenu({ width: 320 });
	$( "#accordion" ).accordion({heightStyle: "content", active: 0});

	$( "#button" ).button();

<?php if($estado != '0' && $estado != 'delcp' && $estado != 'pwdcp') { ?>
	dialog = $( "#dialog" ).dialog({
	    dialogClass: "ui-re-shadow no-close-dialog",
	    autoOpen: false,
	    modal: true,
	    position: { my: "top+25%", at: "center top"},
		    width: 520,
		    height:360,
      	show: {
        	effect: "fade",
       		duration: 500
      	},
      	hide: {
        	effect: "fade",
        	duration: 500
      	},
      	buttons: {
	      "Enviar Datos": function () { $("#update_send").submit(); },
	      "Cancelar": function () { dialog.dialog("close"); } 
      	}
    	});
	$( "#button_edit").button().on( "click", function() {
      		dialog.dialog( "open" );
	});

<?php if (isset($flagfotos)) { ?>
	$( "#button_fotos").button().on( "click", function() {
	 		$('#preloader').fadeIn('fast');
			$('#status').fadeIn('fast');
			window.location='<?php echo base_url() . 'index.php/fotos2/procesa_fotos_pat/' . $historial['placa'] . '/' . $historial['id']; ?>';
	});


<?php } } ?>


<?php if ($estado == 'delcp') { ?>
	
	dialog = $( "#dialog" ).dialog({
	    dialogClass: "ui-re-shadow no-close-dialog",
	    autoOpen: false,
	    modal: true,
	    position: { my: "top+25%", at: "center top"},
		    width: 240,
		    height:170,
      	show: {
        	effect: "fade",
       		duration: 500
      	},
      	hide: {
        	effect: "fade",
        	duration: 500
      	},
      	buttons: {
	      "Eliminar": function () {  $("#delcp_send").submit(); },
	      "Cancelar": function () { dialog.dialog("close"); } 
      	}
    	});

	$( ".btn_del" ).button().on( "click", function() { 
		dialog.dialog( "open" );
		$("#idUser").val(this.value); 
	});

<?php } ?>


<?php if ($estado == 'pwdcp') { ?>
	
	dialog = $( "#dialog" ).dialog({
	    dialogClass: "ui-re-shadow no-close-dialog",
	    autoOpen: false,
	    modal: true,
	    position: { my: "top+25%", at: "center top"},
		    width: 400,
		    height:230,
      	show: {
        	effect: "fade",
       		duration: 500
      	},
      	hide: {
        	effect: "fade",
        	duration: 500
      	},
      	buttons: {
	      "Cambiar Contraseña": function () {  $("#pwdcp_send").submit(); },
	      "Cancelar": function () { dialog.dialog("close"); } 
      	}
    	});

	$( ".btn_pwd" ).button().on( "click", function() { 
		dialog.dialog( "open" );
		$("#idUser").val(this.value); 
	});

<?php } ?>

	$( ".checkbox").checkboxradio();
	var table = $( "#datatable" ).DataTable({"iDisplayStart": 0, "iDisplayLength": 10, "bJQueryUI": true, "sPaginationType": "full_numbers" , "bLengthChange": false, "bFilter": false,
	"oLanguage": {
		"sEmptyTable": "Los datos estan siendo procesados en la base de datos, espere un momento e intente nuevamente.",
		"sInfo": "_START_ a _END_ de un total de _TOTAL_ Items",
		"oPaginate": { sFirst:" « ", sLast:" » ", sNext:" › ",sPrevious:" ‹ "}
        }
	});

	$('#button').click( function() {
		 	$('#preloader').fadeIn('fast');
			$('#status').fadeIn('fast');
		} );

	$( ".decnumber" ).number(true,0,',','.');
});

</script>

</head>