
  <div class="topMenu ui-state-default ui-corner-all">

   <ul class="menu">
   	<li><a href="<?php echo base_url() . 'base/search' ; ?>">Busqueda</a></li>
	<li><a href="<?php echo base_url() . 'base/index/S'; ?>">Vehiculos en stock</a>
<!--	 <ul>
	   <li><a href="#">Proximo remate</a></li>
	   <li><a href="#">En asistencia</a></li>
	</ul>-->
        </li>
	<li><a href="<?php echo base_url() . 'base/index/V'; ?>">Vehiculos en historial</a></li>
	<li>
<a href="<?php if ($pattern) { echo base_url() . 'base/excel/' . $base . '/' . $pattern; } else { echo '#'; } ?>" >Exportar a Excel</a>
	 <ul>
	 <li><a href="<?php echo base_url() . 'base/excel/all/S'; ?>">Stock</a></li>
	  <li><a href="<?php echo base_url() . 'base/excel/all/V'; ?>">Historial</a></li>
	  <!--<li><a href="<?php echo base_url() . 'base/excel/all/P'; ?>">Proximo Remate</a></li>-->
	</ul>
	<li><a href="<?php echo base_url() . 'base/dbmaint'; ?>">Actualizar base de datos</a>
	</li>
	<?php if ($this->session->userdata('proveedor') == 'ALL') { ?>
	<li><a href="#">Administraci&oacute;n</a>
	 <ul>
	   <li><a href="<?php echo base_url() . 'base/usrmaint/addcp'; ?>">Crear acceso para compa&ntilde;ia</a></li>
	   <li><a href="<?php echo base_url() . 'base/usrmaint/delcp'; ?>">Eliminar acceso para compa&ntilde;ia</a></li>
	   <li><a href="<?php echo base_url() . 'base/usrmaint/pwdcp'; ?>">Modificar contrase&ntilde;a de compa&ntilde;ia</a></li>
	   <li><a href="<?php echo base_url() . 'base/clearcache'; ?>">Limpiar cache de medios</a></li>
	 </ul>
        </li>
	<?php } ?>

	<li><a href="<?php echo base_url() . 'login/logout_user'; ?>">Cerrar Sesi&oacute;n</a></li>
    </ul>
<br />
<br />
  </div>
