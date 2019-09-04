
  <div class="topMenu ui-state-default ui-corner-all">

   <ul class="menu">
   	<li><a href="<?php echo site_url('base/search'); ?>">Busqueda</a></li>
	<li><a href="#">Listado de vehiculos</a>
	 <ul>
	   <li><a href="<?php echo site_url('base/index/S'); ?>">En stock</a></li>
	   <li><a href="<?php echo site_url('base/index/V'); ?>">En historial</a></li>
	 </ul>
        </li>
	<li>
	<a href="<?php if ($pattern) { echo site_url('base/excel/' . $base . '/' . $pattern); } else { echo '#'; } ?>" >Exportar a Excel</a>
	 <ul>
	 <li><a href="<?php echo site_url('base/excel/all/S'); ?>">Stock</a></li>
	  <li><a href="<?php echo site_url('base/excel/all/V'); ?>">Historial</a></li>
	</ul>
	<li><a href="<?php echo site_url('base/dbmaint/1'); ?>">Actualizar base de datos</a>
	</li>

	<?php if ($historial) { ?>
	<li><a href="#"><B>OPCIONES DEL ITEM</B></a>
	  <ul>

<?php if($this->session->userdata('allow_update') == 1) { if ((($estado == 'S' || $estado == 'F') && $feedback == 0) && $historial['estado']!="Vendido") { ?>

	    <li><a href="#" onclick="dialog.dialog('open');">Solicitar modificaci&oacute;n de datos</a></li>

<?php } elseif ((($estado == 'S' || $estado == 'F') && $feedback == 1) && $historial['estado']!="Vendido") { ?>	

	    <li><a href="#">Solicitar modificaci&oacute;n de datos</a></li>

<?php } }  ?>

<?php if ($historial['estado']=="Vendido") { ?>

	   <li><a href="<?php echo site_url('doctos/procesa_doctos_siniestro/' . $historial['id'] . '/33/' . $historial['numfac']); ?>">Descargar Factura en PDF</a></li>
  	   <li><a  href="<?php echo site_url('doctos/procesa_doctos_siniestro/' . $historial['id'] . '/43/' . $historial['numliq']); ?>">Descargar Liq. Factura en PDF</a></li> <?php } ?>
          </ul>
	</li>

<?php } ?>
	  
<?php if ($this->session->userdata('proveedor') == 'ALL') { ?>

	<li><a href="#">Admin</a>
	 <ul>
	   <li><a href="<?php echo site_url('base/usrmaint/addcp'); ?>">Crear acceso para compa&ntilde;ia</a></li>
	   <li><a href="<?php echo site_url('base/usrmaint/delcp'); ?>">Eliminar acceso para compa&ntilde;ia</a></li>
	   <li><a href="<?php echo site_url('base/usrmaint/pwdcp'); ?>">Modificar contrase&ntilde;a de compa&ntilde;ia</a></li>
	   <li><a href="<?php echo site_url('base/clearcache/1'); ?>">Limpiar cache de medios</a></li>
	   <li><a href="<?php echo site_url('update/trx_update/1'); ?>">Sincronizar actualizaci&oacute;n</a></li>
	 </ul>
        </li>

<?php } ?>

	<li><a href="<?php echo site_url('login/logout_user'); ?>">Cerrar Sesi&oacute;n</a></li>
    </ul>
<br />
<br />
  </div>
