<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Configuracion de importacion de datos */

$config['filedata_dir']     = 'c:\\devel\\reyco\\aseguradoras\\'; # ruta del archivo de datos
$config['filedata_name']    = 'aseguradora_v4.csv'; # Nombre del archivo
$config['filedata_tcampos'] = 31; # Largo de columnas del archivo CSV

/* configuración de conexión FTP al repositorio de imagenes */

$config['hostname']  = '200.29.162.210'; 	# ftp hostname
$config['username']  = 'user_rematesr_integr';	# ftp username
$config['password']  = 'reyco4355';		# ftp password
$config['rdir']      = '/fotos/'; 		# ftp remote path
$config['ldir']      = 'c:\\devel\\reyco\\aseguradoras\\fotos\\';  # ftp upload path
$config['pdir']      = 'fotos/'; 		# relative path web 
$config['debug_ftp'] = FALSE;			# ftp debugging

/* Configuracion para documentos PDF */

$config['rdir_pdf'] = '/doctos/procesados/'; 			# ftp remote path
$config['ldir_pdf'] = 'c:\\devel\\reyco\\aseguradoras\\pdf\\';  # ftp upload path
$config['pdir_pdf'] = 'pdf/'; 		# relative path web 

$config['fileupload_ldir']      = "c:\\devel\\reyco\\aseguradoras\\upload\\"; # Directorio de carga
$config['fileupload_rdir']      = "/Upload/"; 		# Directorio de carga remoto
$config['fileupload_name']      = 'aseguradora_upload'; # Nombre del archivo
$config['fileupload_procesado'] = '.procesado'; 	# identificacion de archivo procesado
$config['fileupload_debug']     = FALSE; 		# Activar debug para procesamiento de archivos

/* Configuracion servidor de correo */

$config['smtp_host']      = "mail.rematesreyco.cl";
$config['smtp_port']      = "25";
$config['smtp_user']      = "relay@rematesreyco.cl";
$config['smtp_pass']      = "reyco4355";
$config['smtp_from']      = "relay@rematesreyco.cl";
$config['smtp_from_name'] = "Sistema Aseguradoras Reyco";

/* Notificaciones Update, incluir mas con comas */

$config['update_to']   = 'locopol@gmail.com';
$config['debug_email'] = false;

/* MD5 para ccron */
$config['hash_batch'] = '3559a7e033fcfddf7f9657d3123d7823c403fced';

/* CopyRight */

$config['devel_name']    = 'Paul Asalgado';
$config['devel_org']     = 'LionHeart';
$config['devel_contact'] = 'locopol@gmail.com';
$config['devel_ver']     = '4.5';
$config['devel_lic']     = 'Reyco S.A.';
$config['devel_tit']     = 'Sistema de Aseguradoras para multiples compañias.';

?>
