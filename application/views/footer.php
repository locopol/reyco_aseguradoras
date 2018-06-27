<div id="footer" class="ui-state-default ui-corner-all">
<?php 
	$footer_string = $this->config->item('devel_tit') . ' - v: ' . $this->config->item('devel_ver');
	if ($this->session->userdata('isLoggedIn') && $this->session->userdata('proveedor') == 'ALL') 
		$footer_string .= ' - Nombre de usuario: ' . $this->session->userdata('user') . ' - Compa&ntilde;ia: Todas las compa&ntilde;ias';
	if ($this->session->userdata('isLoggedIn') && $this->session->userdata('proveedor') != 'ALL')
		$footer_string .= ' - Nombre de usuario: ' . $this->session->userdata('user') . ' - Descripci&oacute;n: ' . $this->session->userdata('titulo');
	 $footer_string .= '&nbsp;&nbsp;';

	echo $footer_string;
?>
</div>
