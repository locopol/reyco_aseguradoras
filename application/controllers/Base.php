<?php

class Base extends CI_Controller {

	public function __construct() {
			
		parent::__construct();
		$this->config->load('custom_config');
		$this->load->helper(array('form','url','file'));
		$this->load->database();
		$this->load->library(array('session','table','ftp','export'));
		$this->load->model(array('dbmaint','model_fotos', 'model_email'));
		$tmpl=array('table_open' => '<table class="display datatable">');
		$this->table->set_template($tmpl);

	}

	public function excel($base,$pattern)
	{

		$dataExcel = $this->dbmaint->search_db_excel($base,$pattern, $this->session->userdata('proveedor'));

		 if ($dataExcel)  {
			$this->export->to_excel($this->dbmaint->search_db_excel($base,$pattern, $this->session->userdata('proveedor')),'listado');
		 } else {
			$opcion = array('base' => 0, 'pattern' => 0,'result' => 0, 'historial' => 0, 'error' => 'No existen datos para exportar.', 'success' => '', 'login' => '');
			$this->load->view('search', $opcion);
		 }
	}

	public function index($opcion = 'S') {
	
		//carga vistaa
		// caching!
	//	 $this->output->cache(5);

		$opcion = array('estado' => $opcion, 'historial' => '', 'result' => '', 'error' => '', 'success' => '', 'login' => '', 'pattern' => 0);

		$this->load->view('base', $opcion);
	
	}

	public function usrmaint($opcion = 'S') {

		$status = 0;

		switch ($opcion) {
			case 'add':
				if($this->input->post('cp_user') == '')
					$status += 1;
				if($this->input->post('cp_passwd') == '')
					$status += 1;
				if($this->input->post('cp_title') == '')
					$status += 1;

				if ($status >=1) {
					$opcion = array('estado' => 'addcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al crear cuenta, debe completar todos los campos para continuar.', 'login' => '', 'pattern' => 0);

				}
				else
				{
					$retval = $this->dbmaint->add_user($this->input->post('cp_user'), sha1($this->input->post('cp_passwd')), $this->input->post('cp_title'), $this->input->post('cp_company'));

					if ($retval)
						$opcion = array('estado' => 'addcp', 'historial' => '', 'result' => '', 'success' => 'Acceso de compañia (usuario: ' . $this->input->post('cp_user') . ') creado correctamente.', 'error' => '', 'login' => '', 'pattern' => 0);
					else
						$opcion = array('estado' => 'addcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al crear cuenta de acceso, contacte al administrador.', 'login' => '', 'pattern' => 0);

				}

				break;
			case 'del':
				if($this->input->post('idUser') == '')
					$status = 1;

				if ($status >=1) {
					$opcion = array('estado' => 'addcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al eliminar cuenta de acceso ' . $this->dbmaint->get_user($this->input->post('idUser')) . ', intente nuevamente.', 'login' => '', 'pattern' => 0);

				}
				else
				{
					$deletedUser = $this->dbmaint->get_user($this->input->post('idUser'));
					$retval = $this->dbmaint->del_user($this->input->post('idUser'));

					if ($retval)
						$opcion = array('estado' => 'delcp', 'historial' => '', 'result' => '', 'success' => 'Cuenta de acceso ' . $deletedUser . ' eliminada correctamente.', 'error' => '', 'login' => '', 'pattern' => 0);
					else
						$opcion = array('estado' => 'delcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al eliminar cuenta de acceso ' . $deletedUser . ', contacte al administrador.', 'login' => '', 'pattern' => 0);

				}
				break;

			case 'pwd':

				if($this->input->post('pwd1') != $this->input->post('pwd2')) {
					$status =+1; 
					$message = 'Error al modificar contraseña del usuario ' . $this->dbmaint->get_user($this->input->post('idUser')) . ', las contraseñas no coinciden, intente nuevamente.';
				}
				if($this->input->post('pwd1') == '' && $this->input->post('pwd2') == '') {
					$status =+1;
					$message = 'Error al modificar contraseña del usuario ' . $this->dbmaint->get_user($this->input->post('idUser')) . ', complete los campos e intente nuevamente.';
				}

				if ($status >=1) {
					$opcion = array('estado' => 'pwdcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => $message, 'login' => '', 'pattern' => 0);

				}
				else
				{
					$retval = $this->dbmaint->pwd_user($this->input->post('idUser'), sha1($this->input->post('pwd1')));

					if ($retval)
						$opcion = array('estado' => 'pwdcp', 'historial' => '', 'result' => '', 'success' => 'Contraseña del usuario ' . $this->dbmaint->get_user($this->input->post('idUser')) . ' modificada correctamente.', 'error' => '', 'login' => '', 'pattern' => 0);
					else
						$opcion = array('estado' => 'pwdcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al modificar contraseña del usuario ' . $this->dbmaint->get_user($this->input->post('idUser')) . ', contacte al administrador.', 'login' => '', 'pattern' => 0);

				}

				break;
			default:
				$opcion = array('estado' => $opcion, 'historial' => '', 'result' => '', 'error' => '', 'success' => '', 'login' => '', 'pattern' => 0);
				break;
		}

		$this->load->view('admin', $opcion);
	
	}

	public function clearcache()
	{

		$result = 'Limpieza de cache multimedia ejecutado: registros eliminados: ' . $this->model_fotos->delete_fotos_reg() . ', carpetas eliminadas: ' . $this->model_fotos->delete_fotos_media() . '.';
		$opcion = array('estado' => 'S', 'historial' => '', 'result' => '', 'error' => '', 'success' => $result, 'login' => '', 'pattern' => 0);

		$this->load->view('base', $opcion);

	}

	public function historial($id,$estado,$msgtype = "_",$msg = "_") {


	$error = ''; $success = '';
	
	// Notificaciones para carga de fotos

	if($msgtype=='_' && $msg=='_') { $error = ''; $success = ''; }

	if ($msgtype == 'success' && $msg != '_') {
		$error = '';
		$success = $this->model_fotos->get_msg($msg);
	}

	if ($msgtype == 'error'  && $msg != '_') {
		$success = '';
		$error = $this->model_fotos->get_msg($msg);
	}

	// Se incluye mensaje de notificacion de update

	if ($msgtype == 'update') {
		if ($msg == 'success') {
			$error = '';
			$success = 'Solicitud de modificación de datos para el item de inventario ' . $this->dbmaint->get_value_of_col($id,'inventario') . ' procesada correctamente'; }
		if ($msg == 'error') {
			$success = '';
			$error = 'La solicitud de modificación para el item de inventario  ' . $this->dbmaint->get_value_of_col($id,'inventario') . ' no fue procesada, notifique al administrador.'; }
		if ($msg == 'check') {
			$success = '';
			$error = 'La solicitud de modificaci&oacute;n de datos no presenta cambios, modifique al menos un campo antes de enviar.'; }	
		if ($msg == 'nodata') {
			$success = '';
			$error = 'Solicitud no procesada, intente nuevamente.'; }	
	}	

	// modificar fechas vacias (null)

	// NOPE SIRVE!!!!

	
	if ($this->dbmaint->get_value_of_col($id,'siniestro') != 'NOPE') {
	// NOPE NOPE NOPE NOPE NOPE NOPE NOPE NOPE! xD
	
		if ($this->dbmaint->get_value_of_col($id,'fecharecep') == 'N/A') $fecharecep="Sin fecha";
		else $fecharecep = date("d-m-Y", strtotime($this->dbmaint->get_value_of_col($id,'fecharecep')));

 		if ($this->dbmaint->get_value_of_col($id,'fechaliq') == 'N/A') $fechaliq="Sin fecha";
		else $fechaliq = date("d-m-Y", strtotime($this->dbmaint->get_value_of_col($id,'fechaliq')));

		if ($this->dbmaint->get_value_of_col($id,'fecharemate') == 'N/A') $fecharemate="Sin fecha";
		else $fecharemate = date("d-m-Y", strtotime($this->dbmaint->get_value_of_col($id,'fecharemate')));

		$result = array(
		'id' => $id,
		'estado' => $this->dbmaint->get_value_of_col($id,'estado'),
        	'tipo' => $this->dbmaint->get_value_of_col($id,'tipo'),
        	'marca' => $this->dbmaint->get_value_of_col($id,'marca'),
      	 	'modelo' => utf8_decode($this->dbmaint->get_value_of_col($id,'modelo')),
       		'color' => utf8_decode($this->dbmaint->get_value_of_col($id,'color')),
        	'inventario' => $this->dbmaint->get_value_of_col($id,'inventario'),
        	'siniestro' => $this->dbmaint->get_value_of_col($id,'siniestro'),
        	'placa' => $this->dbmaint->get_value_of_col($id,'placa'),
        	'anno' => $this->dbmaint->get_value_of_col($id,'anno'),
        	'chassis' => $this->dbmaint->get_value_of_col($id,'chassis'),
		'motor' => $this->dbmaint->get_value_of_col($id,'motor'),
		'duennoant' => utf8_decode($this->dbmaint->get_value_of_col($id,'duennoant')),
		'rutduennoant' => $this->dbmaint->get_value_of_col($id,'rutduennoant'),
		'funciona' => $this->dbmaint->get_value_of_col($id,'funciona'),
		'numremate' => $this->dbmaint->get_value_of_col($id,'numremate'),
		'numlote' => $this->dbmaint->get_value_of_col($id,'numlote'),
		'condocumento' => $this->dbmaint->get_value_of_col($id,'condocumento'),
		'conllave' => $this->dbmaint->get_value_of_col($id,'conllave'),
		'liquidador' => utf8_decode($this->dbmaint->get_value_of_col($id,'liquidador')),
		'condicionado' => $this->dbmaint->get_value_of_col($id,'condicionado'),
		'numliq' => $this->dbmaint->get_value_of_col($id,'numliq'),
		'fechaliq' => $fechaliq,
		'fecharecep' => $fecharecep,
		'ubicac' => utf8_decode($this->dbmaint->get_value_of_col($id,'ubicac')),
		'comentario' => utf8_decode($this->dbmaint->get_value_of_col($id,'comentario')),
		'proveedor' => utf8_decode($this->dbmaint->get_value_of_col($id,'proveedor')),
		'fecharemate' => $fecharemate,
		'montoadj' => $this->dbmaint->get_value_of_col($id,'montoadj'),
		'montoindem' => $this->dbmaint->get_value_of_col($id,'montoindem'),
		'montomin' => $this->dbmaint->get_value_of_col($id,'montomin')
		);


		//  validar si registro esta a la espera de actualizacion (feedback)	
	
		if ( $this->dbmaint->get_reg_update($result['inventario'],'idInventario') == $result['inventario']) {
				$feedback = 1;
		} else {
				$feedback = 0;
		}
		//$this->output->cache(5);
		
	} else {

		$estado = 0;
	}

	if ($estado == '0')
	{
		$opcion = array('flagfotos' => 1, 'estado' => '', 'base' => 0, 'pattern' => 0, 'result' => 0, 'historial' => 0, 'error' => '', 'success' => '', 'login' => '');

		$this->load->view('search', $opcion);
	}
	else	
	{
		$opcion = array('flagfotos' => 1, 'estado' => $estado, 'historial' => $result ,'error' => $error, 'success' => $success, 'login' => '', 'pattern' => 0, 'feedback' => $feedback);

		$this->load->view('base', $opcion);

	}
	}

	public function dbmaint() {
		//carga datos
		$retval = $this->dbmaint->carga_archivo();

		switch ($retval)
		{
			case -1:
				$opcion = array('estado' => '', 'base' => 0, 'pattern' => 0,'result' => 0, 'historial' => 0, 'error' => '', 'success' => 'Ya existe un proceso de recarga en curso, intentelo mas tarde.', 'login' => '');
				break;
			case 0:

				$opcion = array('estado' => '', 'base' => 0, 'pattern' => 0,'result' => 0, 'historial' => 0, 'error' => '', 'success' => 'Proceso de recarga de base de datos finalizado correctamente', 'login' => '');
				break;
			case 1:
				$opcion = array('estado' => '', 'base' => 0, 'pattern' => 0,'result' => 0, 'historial' => 0, 'error' => 'El limite de columnas del archivo de datos excede el rango permitido, comuniquese con el administrador.', 'success' => '', 'login' => '');
				break;
			case 2:
				$opcion = array('estado' => '', 'base' => 0, 'pattern' => 0,'result' => 0, 'historial' => 0, 'error' => 'No fue posible leer el archivo de datos, comuniquese con el administrador.', 'success' => '', 'login' => '');
				break;
		}


		$this->load->view('search', $opcion);

	
	}

	public function search($oklogin = '') {

		$tmpl=array('table_open' => '<table class="display datatable">');
		$this->table->set_template($tmpl);

		if (!$this->input->post('base'))
		{
			if ($oklogin) $login = 'Bienvenido ' . $this->session->userdata('titulo') . ', Utilice las funciones disponibles en el bot&oacute;n Men&uacute.'; else $login = '';
			
			$opcion = array('estado' => '', 'result' => 0, 'historial' => 0, 'error' => '', 'success' => '', 'login' => $login, 'pattern' => 0);
		} else {

		$sresult = $this->dbmaint->search_db($this->input->post('base'), $this->input->post('pattern'), 'F', $this->session->userdata('proveedor'));

		if (!$sresult) {
			$opcion = array('estado' => '', 'base' => 0, 'pattern' => 0, 'result' => 0, 'historial' => 0, 'error' => 'No se encontraron registros que coincidan con la busqueda.', 'success' => '', 'login' => '');

		} else {
			$opcion = array('estado' => '', 'base' => $this->input->post('base'), 'pattern' => $this->input->post('pattern'), 'result' => $sresult, 'historial' => 0, 'error' => '', 'success' => 'Se encontraron ' . $sresult->num_rows() . ' registros en la busqueda, para exportar, seleccione la opcion de menu Exportar a Excel', 'login' => '');

		}


		}
			$this->load->view('search', $opcion);
	}


	public function update_send($id) {

		// genera Item

		if ($this->input->post('idInventario')) {

		$item = array(
			'idInventario' => $this->input->post('idInventario'),
			'val_monto_indemnizado' => str_replace('.','', $this->input->post('val_monto_indemnizado')),
			'val_monto_minimo' => str_replace('.','', $this->input->post('val_monto_minimo')),
			'val_prox_remate' => $this->input->post('val_prox_remate'),
			'val_old_monto_indemnizado' => $this->dbmaint->get_value_of_col($id, 'montoindem'),
			'val_old_monto_minimo' => $this->dbmaint->get_value_of_col($id, 'montomin'),
			'val_proveedor' => $this->dbmaint->get_value_of_col($id, 'proveedor'),
			'val_placa' => $this->dbmaint->get_value_of_col($id, 'placa'),
			'val_marca' => $this->dbmaint->get_value_of_col($id, 'marca'),
			'val_modelo' => $this->dbmaint->get_value_of_col($id, 'modelo'),
			'val_anno' => $this->dbmaint->get_value_of_col($id, 'anno'));

		if ($this->input->post('val_prox_remate') == 'on')
			$item['val_prox_remate'] = true;
		else
			$item['val_prox_remate'] = false;

		// insertar los registros almacenados

		if ($item['val_monto_indemnizado'] == $item['val_old_monto_indemnizado'] && $item['val_monto_minimo'] == $item['val_old_monto_minimo']) {
			redirect(base_url() . 'base/historial/' . $id . '/S/update/check'); }
		else {
	
		
		$retval = $this->dbmaint->add_reg_update(array('idInventario' => $item['idInventario'], 'val_monto_indemnizado' => $item['val_monto_indemnizado'], 'val_monto_minimo' => $item['val_monto_minimo'], 'val_prox_remate' => $item['val_prox_remate']));

		if ($retval) {
			$this->model_email->send_mail_update($item);
			redirect(base_url() . 'base/historial/' . $id . '/S/update/success');
		} else {
			redirect(base_url() . 'base/historial/' . $id . '/S/update/error');
		}

		}

		} else
		{
			redirect(base_url() . 'base/historial/' . $id . '/S/update/nodata');

		}

	}

	public function devel() {

		show_error('Sistema desarrollado por: ' . $this->config->item('devel_name') . ' (' . $this->config->item('devel_org') . ')' . 
	        ', Desarrollo licenciado para: ' . $this->config->item('devel_lic') . ', Version de desarrollo: ' . $this->config->item('devel_ver') . '<br /><br />Nombre del desarrollo: ' . $this->config->item('devel_tit') . '. <br /><br /> Cualquier reproduccion o modificacion sobre este software se encuentra estrictamente prohibido sin previa autorizacion del desarrollador, no se autoriza replica, copia, extraccion y uso de las funciones de este, la licencia de uso se proporciona solo para el cliente indicado en este mensaje.'	);


	}

	public function test() {

		echo "DONE!";

	}

}

?>
