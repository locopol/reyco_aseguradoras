
  <div class="topMenu">

   <ul class="menu">
	<li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin">Inicio</a></li>

<?php if($this->session->userdata('idRole') == 2 || $this->session->userdata('idRole') == '3') { ?>
<li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/mantenedores/userprofile_do_edit">Configurar Cuenta</a></li>
<?php } if($this->session->userdata('idRole') == 1) { ?>

	<li><a href="#">Mantenedores</a>
            <ul>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/mantenedores/usuarios_do_list">Usuarios</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/mantenedores/tipos_do_list">Tipos de lotes</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/mantenedores/clientes_do_list">Clientes</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/mantenedores/opciones_do_list">Opciones generales</a></li>
	    </ul>
	</li>

        <li><a href="#">Remates</a>
            <ul>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/remates/add">Crear Remate</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/remates/maint">Control de Remates</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/remates/banner">Banner publicitario</a></li>
            </ul>
	</li>
<?php  } if ($this->session->userdata('idRole') == 1 || $this->session->userdata('idRole') == 2 ) { ?>
        <li><a href="#">Lotes</a>
            <ul>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/lotes/upload">Carga masiva</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/lotes/add">Carga individual</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/lotes/maint">Datos de lotes</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/lotes/del">Mantenci&oacute;n</a></li>
            </ul>
	</li>
	<li><a href="#">Medios</a>
            <ul>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/fotos/f_do_maint">Administrador de medios</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/fotos/f_do_remark_maint">Imagenes destacadas</a></li>
            </ul>
	</li>
<?php } if($this->session->userdata('idRole') == 1) { ?> 
	<li><a href="#">Garantias</a>
            <ul>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/garantias/maint">Autorizaciones</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/registro/registro-maint">Adjudicaciones</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/devolucion/devolucion-maint">Devoluciones</a></li>
            </ul>
	</li>

<?php } if($this->session->userdata('idRole') == 1 || $this->session->userdata('idRole') == '3') { ?>
	<li><a href="#">Informes</a>
            <ul>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/informes/resumen-remate-list">Resumen por Remate</a></li>
                <li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_admin/informes/resumen-lotes1-list">Resumen por Lote</a></li>
            </ul>
	</li>

<?php }  ?>
	<li><a href="<?php echo $this->config->item('base_url_app'); ?>/c_login/logout_admin">Cerrar Sesi&oacute;n</a></li>
    </ul>
<br />
<br />
  </div>
