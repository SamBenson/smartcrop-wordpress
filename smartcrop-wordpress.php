<?php 
	/*
		Plugin Name: Smartcrop for WordPress
		Plugin URI: https://github.com/SamBenson/smartcrop-wordpress
		Description: Enables developers to use the fantastic smartcrop.js (https://github.com/jwagner/smartcrop.js) functionality via one simple function. 
		Version: 1.0
		Author: Sam Benson
		Author URI: http://sambenson.co.uk/
		Slug: smartcrop-wordpress
	*/

	function sc_get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}

		return $sizes;
	}

	function sc_get_image_size( $size ) {
		$sizes = sc_get_image_sizes();

		if ( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		}

		return false;
	}

	function the_smart_post_thumbnail( $size = 'thumbnail', $attr = '' ){
		$the_size = sc_get_image_size( $size );
		$the_thumbnail = get_the_post_thumbnail( null, 'full', $attr );

		if('full' == $size){
			echo $the_thumbnail;
			return;
		}
		
		$html  = '<div class="smartcrop" style="width: '.$the_size['width'].'px; height: '.$the_size['height'].'px;" data-width="'.$the_size['width'].'" data-height="'.$the_size['height'].'">';
		$html .= $the_thumbnail;
		$html .= '<canvas></canvas>';
		$html .= '</div>';

		echo $html;
	}

	function sc_add_scripts() {
		wp_enqueue_script('smartcrop', plugins_url('/smartcrop.js', __FILE__), null );
		wp_enqueue_script('smartcrop_main_js', plugins_url('/main.js', __FILE__), array('jquery', 'smartcrop') );
	}
	add_action( 'wp_enqueue_scripts',  'sc_add_scripts');

	function sc_add_styles() {
		wp_enqueue_style('smartcrop_main_css', plugins_url('/main.css', __FILE__), null );
	}
	add_action( 'wp_enqueue_scripts',  'sc_add_styles');