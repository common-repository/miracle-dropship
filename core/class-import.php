<?php

class Miracle_dropship {
    function __construct() {
		
		$this->miracle_user = miracle_get_option( 'miracle_username' );
		}

	public function miracle_check_exist_product($ID){
		global $wpdb;
		$res = $wpdb->get_row("SELECT count(*) as total, post_id as product_id FROM `{$wpdb->prefix}postmeta` WHERE `meta_key`= '_miracle_id' AND `meta_value`='".$ID."'");
		return $res;
	}
	

	/*@GET PRODUCT */
	public function miracle_collect_product_data($importItems = array()){
		
		$Items = miracle_get_user_items( $this->miracle_user );	
		if(!empty($Items ))
		{	
			$count = 0;
			foreach($Items as $key => $row)
			{
				if(in_array($row['id'], $importItems))
				{
					$postData = array();
					$miracle_product_id = (int) $row['id'];
					$product_title = sanitize_text_field($row['name']);
					$product_decription = sanitize_text_field($row['description']);
					$product_price =  sanitize_text_field($row['price']);
					$product_image = sanitize_text_field($row['image_path']);
					
					//CHECK PRODUCT UDPATE
					$postData = $this->miracle_check_exist_product($miracle_product_id);
					if($postData->total > 0 && $postData->product_id != '')
					{
						$product_id = $postData->product_id;
						
						$productData = array(
							 'ID'			=> $product_id,
							 'post_title'   => wp_filter_nohtml_kses($product_title),
							 'post_content' => wp_filter_kses($product_decription),
							 'post_type'    => "product",
						);
					
						wp_update_post( $productData );
						
						if (is_wp_error($productData)) {
							$errors = $post_id->get_error_messages();
							foreach ($errors as $error) {
								echo $error;
							}
						}
						
					} 
					else 
					{
						$productData = array(
							 'post_title'   => wp_filter_nohtml_kses($product_title),
							 'post_content' => wp_filter_kses($product_decription),
							 'post_status'  => "publish",
							 'post_type'    => "product",
						);
						
						$product_id = wp_insert_post( $productData );
						
					}
					
					if($product_id)
					{
						
						update_post_meta( $product_id, '_miracle_id', $miracle_product_id );
						update_post_meta( $product_id, '_price', $product_price );
						
						if(!empty($product_image))
						{
							$attachment_id = media_sideload_image($product_image ,$product_id, $product_title, 'id');
							if($attachment_id > 0 )
							{
								set_post_thumbnail( $product_id, $attachment_id );
							}
						}
										
					}
					$count++;	
				}
			}
			if($count>0){
				
				return $count;
			}
				
		} 
		else 
		{
			return;
		}
		
	}

}