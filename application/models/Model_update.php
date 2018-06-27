<?php

class Model_update extends CI_Model {

	function __construct()
	{
		// Llamar al constructor de CI_Model
		parent::__construct();
		$this->load->model(array('dbmaint'));
		
	}

	function genera_upload($id_feedback) {
	
		// Obtener datos de Feedback para generar archivo y datos
		
		$this->db->select();
		$this->db->where('id',$id_feedback);
		$query=$this->db->get('stocklist_feedback');
		$result_feedback=$query->result();
	
		if ($this->db->affected_rows() != 0) {
	 
			$outfile_csv = fopen($this->config->item('fileupload_ldir') . $result_feedback[0]->fileupload, 'w');

		// genera arreglo

		$feedback_upload = array(
			array('id','idInventario','val_montoindem','val_montomin','val_prox_remate','val_comentario'),
			array($result_feedback[0]->id , $result_feedback[0]->idInventario, $result_feedback[0]->val_monto_indemnizado, $result_feedback[0]->val_monto_minimo, $result_feedback[0]->val_prox_remate,$result_feedback[0]->val_comentario));

		// escribe
	
		foreach ($feedback_upload as $reg) { fputcsv($outfile_csv, $reg, ';', '"'); }

		fclose($outfile_csv);

		} else {

			return 0;

		}
	
	}

	function trx_upload($id = '') {

		$error = 0;
		$retval = 0;
		$x = 0;$y = 0;
   		$config['hostname'] = $this->config->item('hostname');
        	$config['username'] = $this->config->item('username');
        	$config['password'] = $this->config->item('password');
        	$config['port']     = 21;  
        	$config['passive']  = FALSE;
        	$config['debug']    = $this->config->item('debug_ftp');
		
		
		$list_pendientes = $this->dbmaint->get_upload_pendientes();
		$list_enviados = $this->dbmaint->get_upload_enviados();

		if($list_pendientes || $list_enviados) {
			if (!$this->ftp->connect($config))
				return 255;
		}

		// primero procesa los generados para enviarlos y actualiza feedback
		if ($list_pendientes)
		{
			foreach ($list_pendientes as $item) {


				$lfile = $this->config->item('fileupload_ldir') . $item->fileupload;
				$dfile = $this->config->item('fileupload_rdir') . $item->fileupload;
			
				$retval = $this->ftp->upload($lfile, $dfile, '0755');

				if($retval) {
					$error = 1;
					$x += $this->marca_upload_enviado($item->id);

					if ($this->config->item('fileupload_debug'))
						echo $lfile . " OK " . '<br />';
				}
				else {
					if ($this->config->item('fileupload_debug'))
						echo $lfile . " NOK " . '<br />';
					$error = 255;
				}

			}

		}

		// despues extrae los procesados y actualiza feedback
		if ($list_enviados)
		{
			foreach ($list_enviados as $item) {

				$dfile = $this->config->item('fileupload_ldir') . $item->fileupload . $this->config->item('fileupload_procesado');
				$lfile = $this->config->item('fileupload_rdir') . $item->fileupload . $this->config->item('fileupload_procesado');

				$retval = $this->ftp->download($lfile, $dfile, 'ascii');

				if($retval) {
					$error = 1;
					$y += $this->marca_upload_recibido($item->id);
					if ($this->config->item('fileupload_debug'))
						echo $lfile . " OK " . '<br />';
				}
				else {
					if ($this->config->item('fileupload_debug'))
						echo $lfile . " NOK " . '<br />';
					$error = 255;
				}

			}

		}

		if($list_pendientes || $list_enviados) 
			$this->ftp->close(); 

	return $x+$y;

	}

	function verifica_upload($id_feedack) {

		// Obtener datos de Feedback para validar update
		
		$this->db->select();
		$this->db->where('id',$id_feedback);
		$query=$this->db->get('stocklist_feedback');
		$result_feedback=$query->result();
	
		if ($this->db->affected_rows() != 0) {
			if(file_exists($result_feedback[0]->fileupload)) {

				$this->db->set('status_update', 1);
				$this->db->where('id',$id_feedback);
				$this->db->update('stocklist_feedback');

				if ($this->db_affected_rows() == 1)
					return 1;
				else
					return 0;

			} else {
				return 2;
			}
		}
		else {
			return 3;
		}

	}

	function marca_upload_enviado($id_feedback) {

		// Marca de upload enviado de forma implicita
		
		$this->db->set('fileupload_sent', 1);
		$this->db->set('updated_at', date('Y-m-d H:i:s'));
		$this->db->where('id',$id_feedback);
		$this->db->update('stocklist_feedback');

		if ($this->db->affected_rows() == 1)
			return 1;
		else
			return 0;


	}

	function marca_upload_recibido($id_feedback) {

		// Marca de upload recibido de forma implicita
		
		$this->db->set('status_update', 1);
		$this->db->set('updated_at', date('Y-m-d H:i:s'));
		$this->db->where('id',$id_feedback);
		$this->db->update('stocklist_feedback');

		if ($this->db->affected_rows() == 1)
			return 1;
		else
			return 0;


	}

}

?>
