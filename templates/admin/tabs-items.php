<br class="clear" />
<!-- #content -->
<?php
	$is_valid = miracle_check_settings_valid();
	if($is_valid)
	{
		$userId = miracle_get_option( 'miracle_username' );
		$output ='';
		if(isset($_POST['is_import']) && !empty($_POST['is_import']) && is_array($_POST['is_import']))
		{
			$all_items = array();
			foreach($_POST['is_import'] as $item_id)
			{
				$all_items[] = (int) $item_id;
			}
			
			$obj = new Miracle_dropship();
			ob_start();
			$output .= $obj->miracle_collect_product_data($all_items);
			ob_end_clean();
		}
		$Items = miracle_get_user_items( $userId );
		if(isset($Items['error']))
		{
?>
			<div class="miracle-settings-err">
				<p style="background-color: #b83a3a;padding: 14px 10px;border-radius: 7px;color: #ededed;margin-bottom: 26px;font-size: 20px;"><?php _e("Hi Buddy! the entered username is invalid, please make sure you have configured correct miracle username.",MIRACLE_PREFIX); ?></p>
			</div>
<?php
		}
		else
		{
?>
		<div class="inside miracle-items-main">
			<h2><?php _e("Items For You",MIRACLE_PREFIX); ?></h2>
			<div class="miracle-items-wrapper">
			
<?php
			if($output):
?>
				<div class="miracle-item-count">
				<?php _e("Wooh! You have listed $output items to your store.",MIRACLE_PREFIX); ?>
				</div>
			<?php endif; ?>
				<form method="post" id="miracleImportItems">
					<div class="miracle-select-all-wrapper">
						<label><input type="checkbox" id="miracle-select"><?php _e("Select All",MIRACLE_PREFIX); ?></label>						
					</div>
					<div class="miracle-import-top-wrapper">
				 		<button type="submit" class="miracle-import-btn"><?php _e("Import Items",MIRACLE_PREFIX); ?></button>
			 		</div>
					<div class="miracle-col-12 miracle-all-items">
<?php
						$i = 0;
						foreach ($Items as $Item)
						{
							if(isset($Item['image_path']) || $Item['image_path']!='')
							{
								$item_img = $Item['image_path'];
							}
							else
							{
								$item_img = MIRACLE_PLUGINPATH."/templates/admin/images/placeholder-img.png";
							}
							if( $i%4 == 0 )
							{
								echo '<div class="miracle-row">';
							}
	?>
							<div class="miracle-item-wrapper miracle-col-3">
								<div class="miracle-item-img">
									<img src="<?php echo $item_img; ?>">
								</div>
								<div class="miracle-item-info">
									<label>
										<input type="checkbox" class="miracle-item-import" name="is_import[]" value="<?php echo $Item['id']; ?>">
										<h3><?php echo $Item['name']; ?></h3>
									</label>
									<p><?php  echo wp_trim_words( $Item['description'], 15, '...' ); ?></p>
								</div>
							</div>
<?php
							if( $i%4 == 3 )
							{
								echo '</div>';
							}
							$i++;
						}
?>
			 		</div>
					<div class="miracle-import-bottom-wrapper">
					 	<button type="submit" class="miracle-import-btn"><?php _e("Import Items",MIRACLE_PREFIX); ?></button>
					 	<input type="hidden" name="action" value="import-items" />
				 	</div>
				</form>
				<div class="miracle-loader"></div>
			</div>
		</div>
<?php 
			}
?>
			
<?php
	}
	else
	{
?>
		<div class="miracle-settings-err">
			<p style="background-color: #b83a3a;padding: 14px 10px;border-radius: 7px;color: #ededed;margin-bottom: 26px;font-size: 20px;"><?php _e("Hi Buddy! please configure miracle settings to access this page.",MIRACLE_PREFIX); ?></p>
		</div>
<?php
	}
?>