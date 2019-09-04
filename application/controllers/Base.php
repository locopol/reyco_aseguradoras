<?php

class Base extends CI_Controller {

	public function __construct() {
			
		parent::__construct();
		$this->config->load('custom_config');
		$this->load->helper(array('form','url','file'));
		$this->load->database();
		$this->load->library(array('session','table','ftp','export'));
		$this->load->model(array('dbmaint','model_fotos', 'model_email','model_update','model_doctos'));
		$tmpl=array('table_open' => '<table id="datatable" class="display">');
		$this->table->set_template($tmpl);

	}

	public function excel($base,$pattern) {

		$dataExcel = $this->dbmaint->search_db_excel($base,$pattern, $this->session->userdata('company'));

		 if ($dataExcel)  {
			$this->export->to_excel($this->dbmaint->search_db_excel($base,$pattern, $this->session->userdata('company')),'listado');
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

				if($this->input->post('cp_update') == 'on')
					$cp_update = 1;
				else
					$cp_update = 0;

				if ($status >=1) {
					$opcion = array('estado' => 'addcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al crear cuenta, debe completar todos los campos para continuar.', 'login' => '', 'pattern' => 0);

				}

				elseif ($this->dbmaint->get_user_exist($this->input->post('cp_user'))) {
					$opcion = array('estado' => 'addcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al crear cuenta, usuario ya existe, intente nuevamente.', 'login' => '', 'pattern' => 0); }

				elseif (count($this->input->post('cp_company')) == 0) {
					$opcion = array('estado' => 'addcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al crear cuenta, debe seleccionar al menos una compa&ntilde;ia base.', 'login' => '', 'pattern' => 0); }


				else
				{
					$retval = $this->dbmaint->add_user($this->input->post('cp_user'), sha1($this->input->post('cp_passwd')), $this->input->post('cp_title'), 'NOPE', $this->input->post('cp_email'), $cp_update);

					if ($retval) {
						$idUser = $this->dbmaint->get_max_user_id();

						foreach ($this->input->post('cp_company') as $company)
							$this->dbmaint->add_user_company($idUser, $company);
						
						$opcion = array('estado' => 'addcp', 'historial' => '', 'result' => '', 'success' => 'Acceso de compa&ntilde;ia (usuario: ' . $this->input->post('cp_user') . ') creado correctamente.', 'error' => '', 'login' => '', 'pattern' => 0);
					} else {
						$opcion = array('estado' => 'addcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al crear cuenta de acceso, contacte al administrador.', 'login' => '', 'pattern' => 0);
					}
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

					if ($retval) {
						
						$this->dbmaint->del_user_company($this->input->post('idUser'));

						$opcion = array('estado' => 'delcp', 'historial' => '', 'result' => '', 'success' => 'Cuenta de acceso ' . $deletedUser . ' eliminada correctamente.', 'error' => '', 'login' => '', 'pattern' => 0);
					} else {
						$opcion = array('estado' => 'delcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al eliminar cuenta de acceso ' . $deletedUser . ', contacte al administrador.', 'login' => '', 'pattern' => 0);
					}
				}
				break;

			case 'pwd':

				if($this->input->post('pwd1') != $this->input->post('pwd2')) {
					$status =+1; 
					$message = 'Error al modificar contrase&ntilde;a del usuario ' . $this->dbmaint->get_user($this->input->post('idUser')) . ', las contrase&ntilde;as no coinciden, intente nuevamente.';
				}
				if($this->input->post('pwd1') == '' && $this->input->post('pwd2') == '') {
					$status =+1;
					$message = 'Error al modificar contrase&ntilde;a del usuario ' . $this->dbmaint->get_user($this->input->post('idUser')) . ', complete los campos e intente nuevamente.';
				}

				if ($status >=1) {
					$opcion = array('estado' => 'pwdcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => $message, 'login' => '', 'pattern' => 0);

				}
				else
				{
					$retval = $this->dbmaint->pwd_user($this->input->post('idUser'), sha1($this->input->post('pwd1')));

					if ($retval)
						$opcion = array('estado' => 'pwdcp', 'historial' => '', 'result' => '', 'success' => 'Contrase&ntilde;a del usuario ' . $this->dbmaint->get_user($this->input->post('idUser')) . ' modificada correctamente.', 'error' => '', 'login' => '', 'pattern' => 0);
					else
						$opcion = array('estado' => 'pwdcp', 'historial' => '', 'result' => '', 'success' => '', 'error' => 'Error al modificar contrase&ntilde;a del usuario ' . $this->dbmaint->get_user($this->input->post('idUser')) . ', contacte al administrador.', 'login' => '', 'pattern' => 0);

				}

				break;
			default:
				$opcion = array('estado' => $opcion, 'historial' => '', 'result' => '', 'error' => '', 'success' => '', 'login' => '', 'pattern' => 0);
				break;
		}

		$this->load->view('admin', $opcion);
	
	}

	public function clearcache($level = '', $hash = '') {

		if($hash == $this->config->item('hash_batch') || $this->session->userdata('isLoggedIn')) {

		// Limpiar y calcular

		$result_rbase = $this->model_fotos->delete_fotos_reg() +  $this->model_doctos->delete_doctos_reg();
		$result_files = $this->model_fotos->delete_fotos_media() + $this->model_doctos->delete_doctos_media();

		$result = 'Limpieza de cache multimedia ejecutado: registros eliminados: ' . $result_rbase . ', carpetas eliminadas: ' . $result_files . '.';
		$opcion = array('estado' => 'S', 'historial' => '', 'result' => '', 'error' => '', 'success' => $result, 'login' => '', 'pattern' => 0);

			if ($level == 0)
				echo($result);

			if ($level == 1)
				$this->load->view('base', $opcion);

		}

		else {
			$opcion = array('estado' => 'S', 'historial' => '', 'result' => '', 'error' => '', 'success' => 'No se realiza ninguna acci&oacute;n.', 'login' => '', 'pattern' => 0);
			$this->load->view('base', $opcion);
		
		}

	}

	public function historial($id = -1,$estado = -1,$msgtype = "_",$msg = "_") {

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
			$success = 'Solicitud de modificaci&oacute;n de datos para el item de inventario ' . $this->dbmaint->get_value_of_col($id,'inventario') . ' procesada correctamente'; }
		if ($msg == 'error') {
			$success = '';
			$error = 'La solicitud de modificaci&oacute;n para el item de inventario  ' . $this->dbmaint->get_value_of_col($id,'inventario') . ' no fue procesada, notifique al administrador.'; }
		if ($msg == 'check') {
			$success = '';
			$error = 'La solicitud de modificaci&oacute;n de datos no presenta cambios, modifique al menos un campo antes de enviar.'; }	
		if ($msg == 'nodata') {
			$success = '';
			$error = 'Solicitud no procesada, intente nuevamente.'; }	
	}	

	// NOPE SIRVE!!!!
	
	if ($this->dbmaint->get_value_of_col($id,'siniestro') != 'NOPE') {
	// NOPE NOPE NOPE NOPE NOPE NOPE NOPE NOPE! xD

		// Obtener arreglo con datos completos
		// 
		$result = $this->dbmaint->get_value_row($id);
		//  validar si registro esta a la espera de actualizacion (feedback)

		$result_feedback = $this->dbmaint->get_status_feedback($result['inventario']);
		
		if ($result_feedback)
			if($result_feedback->fileupload_sent == true)
				if($result_feedback->status_update == true)
					$feedback=0;
				else
					$feedback=1;
			else
				$feedback=1;
		else 
			$feedback = 0;

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

	public function dbmaint($level = '', $hash = '') {

		if($hash == $this->config->item('hash_batch') || $this->session->userdata('isLoggedIn')) {

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

			if ($level == 0)
				echo($opcion['success'] == '' ? $opcion['error'] : $opcion['success']);

			if ($level == 1)
				$this->load->view('search', $opcion);

		}

		else {

			$opcion = array('estado' => 'S', 'historial' => '', 'result' => '', 'error' => '', 'success' => 'No se realiza ninguna acci&oacute;n.', 'login' => '', 'pattern' => 0);
			$this->load->view('base', $opcion);
		
		}
	
	}

	public function search($oklogin = '') {

		if (!$this->input->get('base'))
		{
			if ($oklogin) $login = 'Bienvenido ' . $this->session->userdata('titulo') . ', Utilice las funciones disponibles en el bot&oacute;n Men&uacute.'; else $login = '';
			
			$opcion = array('estado' => '', 'result' => 0, 'historial' => 0, 'error' => '', 'success' => '', 'login' => $login, 'pattern' => 0);
		} else {

		$sresult = $this->dbmaint->search_db($this->input->get('base'), $this->input->get('pattern'), 'F', $this->session->userdata('company'));

		if (!$sresult) {
			$opcion = array('estado' => '', 'base' => 0, 'pattern' => 0, 'result' => 0, 'historial' => 0, 'error' => 'No se encontraron registros que coincidan con la busqueda.', 'success' => '', 'login' => '');

		} else {
			$opcion = array('estado' => '', 'base' => $this->input->get('base'), 'pattern' => $this->input->get('pattern'), 'result' => $sresult, 'historial' => 0, 'error' => '', 'success' => 'Se encontraron ' . $sresult->num_rows() . ' registros en la busqueda, para exportar, seleccione la opcion de menu Exportar a Excel', 'login' => '');

		}

		}
			$this->load->view('search', $opcion);
	}

	public function devel() {

		show_error('Sistema desarrollado por: ' . $this->config->item('devel_name') . ' (' . $this->config->item('devel_org') . ')' . 
	        ', Desarrollo licenciado para: ' . $this->config->item('devel_lic') . ', Version de desarrollo: ' . $this->config->item('devel_ver') . '<br /><br />Nombre del desarrollo: ' . $this->config->item('devel_tit') . '. <br /><br /> Cualquier reproduccion o modificacion sobre este software se encuentra estrictamente prohibido sin previa autorizacion del desarrollador, no se autoriza replica, copia, extraccion y uso de las funciones de este, la licencia de uso se proporciona solo para el cliente indicado en este mensaje.'	);

	}

	public function test() {

		print_r($this->dbmaint->get_value_row(25351621));
	}

}

?>
