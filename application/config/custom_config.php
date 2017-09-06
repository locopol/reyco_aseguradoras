<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Configuracion de importacion de datos */

$config['filedata_dir'] = 'c:\\temp\\aseguradora_v4\\'; # ruta del archivo de datos
$config['filedata_name'] = 'aseguradora_v4.csv'; # Nombre del archivo

/* configuración de conexión FTP al repositorio de imagenes */

$config['hostname'] = 'localhost'; 	# ftp hostname
$config['username'] = 'test';		# ftp username
$config['password'] = 'test';		# ftp password
$config['rdir'] = '/'; 			# ftp remote path
$config['ldir'] = 'c:\\devel\\aseguradoras\\fotos\\';  # ftp upload path
$config['pdir'] = 'fotos/'; 		# relative path web 
$config['debug_ftp'] = FALSE;		# ftp debugging

/* Configuracion servidor de correo */

$config['smtp_host'] = "mail.rematesreyco.cl";
$config['smtp_port'] = "25";
$config['smtp_user'] = "relay@rematesreyco.cl";
$config['smtp_pass'] = "reyco4355";
$config['smtp_from'] = "relay@rematesreyco.cl";
$config['smtp_from_name'] = "Sistema Aseguradoras Reyco";

/* Notificaciones Update, incluir mas con comas */

$config['update_to'] = 'locopol@gmail.com';
$config['debug_email'] = false;

/* CopyRight */

$config['devel_name'] = 'Paul Asalgado';
$config['devel_org'] = 'LionHeart';
$config['devel_contact'] = 'locopol@gmail.com';
$config['devel_ver'] = '4.4';
$config['devel_lic'] = 'Reyco S.A.';
$config['devel_tit'] = 'Sistema de Aseguradoras para multiples compañias.';

?>
