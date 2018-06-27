<?php

class Model_doctos extends CI_Model {

	function __construct()
	{
		// Llamar al constructor de CI_Model
		parent::__construct();
	
	}

	function get_doctos($idDocto, $idSiniestro) {

		// FORMATO SUPUESTO DE ARCHIVO
		// <PREFIJO 33-43>_<siniestro>-.pdf

		if ($idDocto == 33)
			$lfile_named = 'doc_factura_' . $idDocto . '_' . $idSiniestro . '.pdf';

		if ($idDocto == 43)
			$lfile_named = 'doc_liq_factura_' . $idDocto . '_' . $idSiniestro . '.pdf';

		$rfile_named = $idDocto . '-' . $idSiniestro . '.pdf';

		$error = 0;
		$retval = 0;
		$x = 0;$y = 0;
   		$config['hostname'] = $this->config->item('hostname');
        	$config['username'] = $this->config->item('username');
        	$config['password'] = $this->config->item('password');
        	$config['port']     = 21;  
        	$config['passive']  = FALSE;
        	$config['debug']    = $this->config->item('debug_ftp');
		
		$err = $this->ftp->connect($config);

		if ($err == "")
			return 255;

		$list = $this->ftp->list_files($this->config->item('rdir_pdf') . $rfile_named);

		if ($list[0])
		{

			if (!is_dir($this->config->item('ldir_pdf') . $idSiniestro))			
				mkdir($this->config->item('ldir_pdf') . $idSiniestro);

			$retval = $this->ftp->download(
				$this->config->item('rdir_pdf') . $rfile_named, 
				$this->config->item('ldir_pdf') . $idSiniestro . '//' . $lfile_named, 'binary'
			);
			
		}

				if($retval)
					$error = 1;
				else
					$error = 255;
			
		$this->ftp->close(); 

	return $error;

	}

	function del_docs($idSiniestro) {

		if (is_dir($this->config->item('ldir_pdf') . $idSiniestro)) {
		    	foreach (scandir($this->config->item('ldir_pdf') . $idSiniestro) as $item) {
		        	if ($item == '.' || $item == '..') continue;
				else unlink($this->config->item('ldir_pdf') . $idSiniestro . '/' . $item);
			}

			rmdir($this->config->item('ldir_pdf') . $idSiniestro);

		} else {
			return 0;
		}

	return 1;
			
	}

	function insert_reg($idDocto, $idSiniestro) {

		// FORMATO SUPUESTO DE ARCHIVO
		// <PREFIJO 33-43>_<siniestro>-.pdf

		if ($idDocto == 33)
			$lfile_named = 'doc_factura_' . $idDocto . '_' . $idSiniestro . '.pdf';

		if ($idDocto == 43)
			$lfile_named = 'doc_liq_factura_' . $idDocto . '_' . $idSiniestro . '.pdf';

		$data = array(
			'idSiniestro' => $idSiniestro,
			'documento' => $this->config->item('pdir_pdf') . $idSiniestro . '/' . $lfile_named,
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('doctos', $data); 

   	  	if ($this->db->affected_rows() == 0)
			return 1;
		else
			return 0;

	}

	function delete_doctos_reg() {

		$this->db->empty_table('doctos'); 

   	  	if ($this->db->affected_rows() == 0)
			return 0;
		else
			return $this->db->affected_rows();

	}

	function delete_doctos_media() {
		
		$x = 0;

		if (is_dir($this->config->item('ldir_pdf'))) {
			foreach (scandir($this->config->item('ldir_pdf')) as $item)
				$x++;
		
			delete_files($this->config->item('ldir_pdf'), TRUE);		
		} else {
			return 0;
		}

		return ($x-2);
			
	}

	function check_reg($idSiniestro) {

		$sql = "SELECT idSiniestro FROM doctos WHERE idSiniestro = ?";

		$this->db->query($sql, array($idSiniestro)); 

		if ($this->db->affected_rows() == 0)
			return 0;
		else
			return 1;

	}

	function doc_path($idSiniestro) {

		$this->db->select('documento');
		$this->db->where('idSiniestro', $idSiniestro);
		$query=$this->db->get('doctos');
		$result=$query->result();

		if ($this->db->affected_rows() == 0)
			return 0;

		return $result[0]->documento;

	}
		
	function docto_path($idSiniestro) {

		$sql='select documento from doctos where idSiniestro = \'' . $idSiniestro .  '\'';
		$query=$this->db->query($sql);

   	  	if ($this->db->affected_rows() == 1)
		{
			$retval = array(); $x = 0;

			foreach (scandir($this->config->item('ldir_pdf') . $idSiniestro) as $item) {
				$efile = explode(".", strtolower($item));
				if ($item == '.' || $item == '..') continue;
				else $retval[$x] = $this->config->item('pdir_pdf') . $idSiniestro . '/' . $item;
				$x++;
			}
		
			if (count($retval) == 0)
			  $retval = 0;

		}
		else
		{
			return 0;
		}

	return $retval;
	
	}

	function get_msg($id) {

		switch ($id)
		{

			case '-1':
				$retval = 'Los documentos ya fueron procesados, no se realiza ninguna acci&oacute;n.';
				break;
			case '1':
				$retval = 'Documento obtenido correctamente desde el servidor.';
				break;
			case '0':
				$retval = 'No se encontraron documentos disponibles para el vehiculo seleccionado.';
				break;
			case '255':
				$retval = 'Error de comunicaci&oacute;n con el servidor de documentos, contactese con el administrador.';
				break;
			default:
				$retval = 'No se realizo ninguna acccion';
		}

	 return $retval;
	
	}

}

?>
