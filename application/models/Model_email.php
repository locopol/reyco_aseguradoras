<?php

class Model_email extends CI_Model {

	function __construct()
	{
		// Llamar al constructor de CI_Model
		parent::__construct();

		$this->load->library('email');

		$this->_emailconf = array();
		$this->_emailconf['protocol'] = 'smtp';
		$this->_emailconf['smtp_host'] = $this->config->item('smtp_host');
		$this->_emailconf['smtp_port'] = $this->config->item('smtp_port');
		$this->_emailconf['smtp_user'] = $this->config->item('smtp_user');
		$this->_emailconf['smtp_pass'] = $this->config->item('smtp_pass');
		$this->_emailconf['smtp_timeout'] = 10;
		$this->_emailconf['mailtype'] = 'text';
		$this->_emailconf['charset'] = 'iso-8859-1';	

		$this->email->initialize($this->_emailconf);
	}

	function send_mail_update($item) 
	{
		// Alterar los valores para el correo

		if ($item['val_monto_minimo'] == $item['val_old_monto_minimo'])
			$item['val_monto_minimo'] = 'Sin cambios';

		if ($item['val_monto_indemnizado'] == $item['val_old_monto_indemnizado'])
			$item['val_monto_minimo'] = 'Sin cambios';

		if ($item['val_prox_remate'] == true)
			$item['val_prox_remate'] = 'Si';
		else
			$item['val_prox_remate'] = 'No';

		if ($item['val_aplicaiva'] == true)
			$item['val_aplicaiva'] = 'Si';
		else
			$item['val_aplicaiva'] = 'No';

		$connection = @fsockopen( $this->config->item('smtp_host'),  $this->config->item('smtp_port'));
		if (is_resource($connection))
		{

		$this->email->set_newline("\r\n");
		$this->email->from($this->config->item('smtp_from'), $this->config->item('smtp_from_name'));
		
		if ($this->session->userdata('email') != null) 
		    $this->email->to(array($this->config->item('update_to'), $this->session->userdata('email')));
		else
			$this->email->to($this->config->item('update_to'));
				
		$this->email->cc($this->config->item('update_cc'));

		$this->email->subject('(Siniestro ' . $item['val_siniestro'] . ') solicitud de modificación aseguradoras.');

		$message  = $this->config->item('devel_tit') . ' ha recibido la siguiente solicitud de modificación:' . "\r\n\r\n";
		$message .= ' - Numero de inventario: ' . $item['idInventario'] . "\r\n";
		$message .= '  - Patente : ' . $item['val_placa'] . "\r\n";
		$message .= '  - Marca   : ' . $item['val_marca'] . "\r\n";
		$message .= '  - Modelo  : ' . $item['val_modelo'] . "\r\n";
		$message .= '  - Año     : ' . $item['val_anno'] . "\r\n";
		$message .= '  - Compañia: ' . $item['val_proveedor'] . "\r\n\r\n";
		$message .= ' - Nuevo Monto Indemnizado: ' . $item['val_monto_indemnizado'] . "\r\n";
		$message .= ' - Nuevo Monto Minimo: ' .  $item['val_monto_minimo'] . "\r\n";
		$message .= ' - Incluir en proximo remate?: ' . $item['val_prox_remate']  . "\r\n";
		$message .= ' - Aplica IVA?: ' . $item['val_aplicaiva']  . "\r\n";
		$message .= ' - Comentario de compañia: ' .  $item['val_comentario'] . "\r\n\r\n";
		$message .= 'Este cambio se procesará automaticamente en el sistema de remates cuando se revise la información del vehiculo.' . "\r\n\r\n";
		$message .= 'Correo generado automaticamente por ' . $this->config->item('smtp_from_name');

		$this->email->message($message);
		
		if ($this->config->item('debug_email') == true)
		{
		  $this->email->send(FALSE);
		  echo $this->email->print_debugger(array('headers', 'subject', 'body'));
			
		} else {
		  $this->email->send();
		}
		
		} else 	{
		  log_message('error', 'No se pudo enviar correo smtp (update), to: ' . $to);
		}
	}

}

?>
