<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Configuracion de importacion de datos */

$config['filedata_dir'] = '/home/rematesr/'; # ruta del archivo de datos
$config['filedata_name'] = 'aseguradora_v4.csv'; # Nombre del archivo

/* configuracion de conexión FTP al repositorio de imagenes */

$config['hostname'] = '200.29.162.210'; 	        # ftp hostname
$config['username'] = 'user_rematesr_integr';		# ftp username
$config['password'] = 'reyco4355';		            # ftp password
$config['rdir'] = '/'; 			                    # ftp remote path
$config['ldir'] = '/home/rematesr/public_html/aseguradora_v4/fotos/';  # ftp upload path
$config['pdir'] = 'fotos/'; 	                	# relative path web 
$config['debug_ftp'] = false;	                    # ftp debugging

/* Configuracion servidor de correo */

$config['smtp_host'] = "mail.rematesreyco.cl";
$config['smtp_port'] = "25";
$config['smtp_user'] = "relay@rematesreyco.cl";
$config['smtp_pass'] = "reyco4355";
$config['smtp_from'] = "relay@rematesreyco.cl";
$config['smtp_from_name'] = "Sistema Aseguradoras V4";

/* Notificaciones Update, incluir mas con comas */

//$config['update_to'] = 'locopol@gmail.com';
$config['update_to'] = 'gdeferari@rematesreyco.cl, carmijo@rematesreyco.cl, mcabrera@rematesreyco.cl, pbauerle@rematesreyco.cl';
$config['debug_email'] = false;

/* CopyRight */

$config['devel_name'] = 'Paul Asalgado';
$config['devel_org'] = 'LionHeart';
$config['devel_contact'] = 'locopol@gmail.com';
$config['devel_ver'] = '4.3';
$config['devel_lic'] = 'Reyco S.A.';
$config['devel_tit'] = 'Sistema de Aseguradoras para multiples compañias.';

?>