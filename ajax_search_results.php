<?php 


	/** * This file is an independent controller, used to query the WordPress database 
	* and provide search results for Ajax requests. 
	* 
	* @return string   Either return nothing (i.e. no results) or return some formatted results. 
	*/ 
	if (!defined('WP_PLUGIN_URL')) {   
		// WP functions become available once you include the config file   
		require_once( realpath('../../../').'/wp-config.php' ); 
	}
	 // No point in executing a query if there's no query string
	if ( empty($_GET['s']) ) {   
		exit; 
	 }
	//src 	https://codex.wordpress.org/Class_Reference/WP_Query nested category handling

	$max_posts = 10;
	switch(true){
		case(!empty($_GET['color']) AND !empty($_GET['category_name'])):
			$args = array(
			'relation' => 'AND',
			'post_type' => 'product',
			's' => $_GET['s'],
			'showposts' => $max_posts,
			'product_cat'=>$_GET['category_name'],
			'tax_query' => array(
				array(
					'taxonomy' => 'pa_color-swatches',
					'field'    => 'slug',
					'terms' => $_GET['color'] // value of attribute
				  )
			   ),
			);
			$WP_Query_object = new WP_Query( );
			$WP_Query_object->query($args);
			break;
		case(!empty($_GET['color'])):
			 $args = array(
			'relation' => 'AND',
			'post_type' => 'product',
			's' => $_GET['s'],
			'showposts' => $max_posts,
			'tax_query' => array(
				array(
					'taxonomy' => 'pa_color-swatches',
					'field'    => 'slug',
					'terms' => $_GET['color'] // value of attribute
				  )
			),
			);
			$WP_Query_object = new WP_Query( );
			$WP_Query_object->query($args); 
			break;
		case(!empty($_GET['category_name'])):
			$args = array(
			'post_type' => 'product',
			's' => $_GET['s'],
			'showposts' => $max_posts,
			'product_cat'=>$_GET['category_name'],
			);
			$WP_Query_object = new WP_Query( );
			$WP_Query_object->query($args);
			break;
		default:
		$args = array(
			'post_type' => 'product',
			's' => $_GET['s'],
			'showposts' => $max_posts,
			);
			$WP_Query_object = new WP_Query( );
			$WP_Query_object->query($args);
			break;
	}
	
	  
	// If there are no results... 

	if (! count($WP_Query_object->posts) ){   
	print file_get_contents( 'tpls/no_results.tpl');      
	exit; 
	} 
	// Otherwise, format the results
	$container = array('content'=>''); // define the container's only placeholder 
	$single_tpl = file_get_contents( 'tpls/single_result.tpl');   
	 foreach($WP_Query_object->posts as $result) {
		$result->permalink = get_permalink($result->ID); 
		$result->thumbnail=get_the_post_thumbnail( $result->ID,'post-thumbnail' );
		$product = wc_get_product( $result->ID);
		$product_price= wc_get_price_including_tax( $product );
		if (!empty ($product_price) ){$result->price= $product_price.'<span class="woocommerce-Price-currencySymbol">€</span>';}
		else{$result->price='select variant';}
		$res_post= get_post($result->ID);
		$result->short_description=wp_trim_words(apply_filters( 'the_content', $res_post->post_content ), 20 );
		$container['content'] .= parse($single_tpl, $result);
	} 
	// Wrap the results 
	$results_container_tpl = file_get_contents( 'tpls/results_container.tpl'); 
	print parse($results_container_tpl, $container);
	/** 
	* parse 
	* 
	* A simple parsing function for basic templating. 
	* 
	* @param $tpl   string    A formatting string containing [+placeholders+] 
	* @param $hash   array   An associative array containing keys and values e.g. array('key' => 'value'); 
	* @return   string      Placeholders corresponding to the keys of the hash will be replaced with the values the resulting string will be returned. 
	*/ 
	function parse($tpl, $hash) {
		foreach ($hash as $key => $value) {        
		$tpl = str_replace('[+'.$key.'+]', $value, $tpl);    
		}    
		return $tpl; 
	}
 /* EOF */ 
 
