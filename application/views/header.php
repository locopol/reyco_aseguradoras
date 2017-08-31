<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
<meta charset="latin1">
<title>Sistema de aseguradora de vehiculos</title>
<script src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script> 
<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>js/jquery-tabs.js"></script>

<script src="<?php echo base_url();?>js/jquery.datatables.min.js"></script>
<script src="<?php echo base_url();?>js/blueimp-gallery.min.js"></script>
<script src="<?php echo base_url();?>js/jqsimplemenu.js"></script>
<script src="<?php echo base_url();?>js/jquery.number.min.js"></script>
<link href="<?php echo base_url();?>css/blueimp-gallery.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>css/jquery-ui.min.css" rel="stylesheet"/>
<link href="<?php echo base_url();?>css/jqsimplemenu.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>css/private.css" rel="stylesheet" type="text/css" />

<style>
.ui-re-shadow {
	position:relative;
	z-index:1;
	box-shadow: 0px 2px 8px #888888;
	border-radius: 8px
}
</style>

<script>

var dialog;

$(function() {
	$('.menu').jqsimplemenu(); 
	$( "#selector" ).selectmenu({ width: 320 });
	$( "#accordion" ).accordion({heightStyle: "content", active: 0});
	$( ".menutab" ).tabSlideOut({
      		tabHandle: ".tab",
      		pathToTabImage: "<?php echo base_url();?>images/menu.png",
      		imageHeight: "37px",
      		imageWidth: "77px",
      		tabLocation: "top",
      		speed: 300,
      		action: "click",
      		leftPos: "650px",
      		fixedPosition: true
    	});
	$( "#button" ).button();

<?php if($estado != '0' ) { ?>
	dialog = $( "#dialog" ).dialog({
	    dialogClass: "ui-re-shadow no-close-dialog",
	    autoOpen: false,
	    modal: true,
	    position: { my: "top+25%", at: "center top"},
		    width: 440,
		    height:280,
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
	$( ".datatable" ).dataTable({"iDisplayStart": 0, "iDisplayLength": 10, "bJQueryUI": true, "sPaginationType": "full_numbers" , "bLengthChange": false, "bFilter": false,
	"oLanguage": {
"sEmptyTable": "Los datos estan siendo procesados en la base de datos, espere un momento e intente nuevamente."
}
	});
	$('#button').click( function() {
		 	$('#preloader').fadeIn('fast');
			$('#status').fadeIn('fast');
		} );

	$( ".decnumber" ).number(true,0,',','.');
});

</script>

<style>
/* Preloader */
#preloader {
	position:absolute;
	top:0;
	left:0;
	right:0;
	bottom:0;
	background: linear-gradient(to right,darkgrey, lightgrey);
	//background: url(/aseguradora_v4/images/bgsite.jpg)  no-repeat fixed center;
	background-size: cover;
	z-index:1001; /* makes sure it stays on top */
}
#status {
	width:200px;
	height:200px;
	position:absolute;
	left:50%; /* centers the loading animation horizontally one the screen */
	top:50%; /* centers the loading animation vertically one the screen */
	background-image: url(/aseguradora_v4/images/loading2.gif); /* path to your loading animation */
	background-repeat:no-repeat;
	background-position:center;
	margin:-100px 0 0 -100px; /* is width and height divided by two */
}
</style>

</head>
