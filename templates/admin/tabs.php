<div id="content">
	<h2 class="nav-tab-wrapper">

		 <a data-tab-id="dashboard" class="nav-tab<?php miracle_admin_active_tab( 'overview' ); ?>" href="<?php echo add_query_arg( array( 'page' => MIRACLE_PREFIX, 'tab' => 'overview' ), 'admin.php' ); ?>"><?php _e( 'Dashboard', MIRACLE_PREFIX ); ?></a>

		  <a data-tab-id="items" class="nav-tab<?php miracle_admin_active_tab( 'items' ); ?>" href="<?php echo add_query_arg( array( 'page' => MIRACLE_PREFIX, 'tab' => 'items' ), 'admin.php' ); ?>"><?php _e( 'Items For You', MIRACLE_PREFIX ); ?></a>

		  <a data-tab-id="settings" class="nav-tab<?php miracle_admin_active_tab( 'settings' ); ?>" href="<?php echo add_query_arg( array( 'page' => MIRACLE_PREFIX, 'tab' => 'settings' ), 'admin.php' ); ?>"><?php _e( 'Settings', MIRACLE_PREFIX ); ?></a>
	</h2>
	<?php miracle_tab_template( $tab ); ?>

</div>
<!-- #content -->