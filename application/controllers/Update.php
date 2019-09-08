<?php

class Update extends CI_Controller {

	public function __construct() {
			
		parent::__construct();
		$this->config->load('custom_config');
		$this->load->helper(array('form','url','file'));
		$this->load->database();
		$this->load->library(array('session','table','ftp','export'));
		$this->load->model(array('dbmaint', 'model_email','model_update'));
		$tmpl=array('table_open' => '<table class="display datatable">');
		$this->table->set_template($tmpl);

	}

	public function update_send($id) {

		// genera Item

		if ($this->input->post('idInventario')) {

		$item = array(
			'idInventario' => $this->input->post('idInventario'),
			'val_monto_indemnizado' => str_replace('.','', $this->input->post('val_monto_indemnizado')),
			'val_monto_minimo' => str_replace('.','', $this->input->post('val_monto_minimo')),
			'val_prox_remate' => $this->input->post('val_prox_remate'),
			'val_comentario' => $this->input->post('val_comentario'),
			'val_old_monto_indemnizado' => $this->dbmaint->get_value_of_col($id, 'montoindem'),
			'val_old_monto_minimo' => $this->dbmaint->get_value_of_col($id, 'montomin'),
			'val_old_comentario' => $this->dbmaint->get_value_of_col($id, 'comentario'),
			'val_proveedor' => $this->dbmaint->get_value_of_col($id, 'proveedor'),
			'val_placa' => $this->dbmaint->get_value_of_col($id, 'placa'),
			'val_marca' => $this->dbmaint->get_value_of_col($id, 'marca'),
			'val_modelo' => $this->dbmaint->get_value_of_col($id, 'modelo'),
			'val_anno' => $this->dbmaint->get_value_of_col($id, 'anno'),
			'val_siniestro' => $this->dbmaint->get_value_of_col($id,'siniestro'));

		if ($this->input->post('val_prox_remate') == 'on')
			$item['val_prox_remate'] = true;
		else
			$item['val_prox_remate'] = false;

		if ($this->input->post('val_aplicaiva') == 'on')
			$item['val_aplicaiva'] = true;
		else
			$item['val_aplicaiva'] = false;

		// insertar los registros almacenados

		if ($item['val_monto_indemnizado'] == $item['val_old_monto_indemnizado'] && $item['val_monto_minimo'] == $item['val_old_monto_minimo'] && $item['val_comentario'] == $item['val_old_comentario']) {
			redirect(site_url('base/historial/' . $id . '/S/update/check')); }
		else {
		
		$retval = $this->dbmaint->add_reg_feedback($item);

		if ($retval) {
			$this->model_update->genera_upload($retval);
			$this->model_update->trx_upload();
			$this->model_email->send_mail_update($item);
				if ($this->config->item('debug_email') == true)
					return 0;
			
			redirect(site_url('base/historial/' . $id . '/S/update/success'));
		} else {
			redirect(site_url('base/historial/' . $id . '/S/update/error'));
		}

		}

		} else
		{
			redirect(site_url('base/historial/' . $id . '/S/update/nodata'));

		}

	}

	public function trx_update($level = '',$hash = '') {

		if($hash == $this->config->item('hash_batch') || $this->session->userdata('isLoggedIn')) {

			$result_trx = $this->model_update->trx_upload();

			if ($result_trx == 255) {
				$result = 'Sincronizacion de datos Fallida, contacte al administrador.';
				$opcion = array('estado' => '', 'base' => 0, 'historial' => '', 'result' => '', 'error' => $result, 'success' => '', 'login' => '', 'pattern' => 0);

			}
			else {

			$result = 'Sincronizacion de datos realizada, feedback total: ' . $result_trx . '.';
			$opcion = array('estado' => '', 'base' => 0, 'historial' => '', 'result' => '', 'error' => '', 'success' => $result, 'login' => '', 'pattern' => 0);

			}

			if ($level == 0)
				echo($result);

			if ($level == 1)
				$this->load->view('search', $opcion);

		}

		else {
			$opcion = array('estado' => 'S', 'historial' => '', 'result' => '', 'error' => '', 'success' => 'No se realiza ninguna acci&oacute;n.', 'login' => '', 'pattern' => 0);
			$this->load->view('base', $opcion);
		
		}

	}

}

?>
