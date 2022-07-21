<?php
/**
 * Trader Slideshow Plugin
 *
 * @package Trader_Slideshow_Plugin
 * @version 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Trader Slideshow Plugin
 * Description: A plugin used to display a slideshow of user-added images using shortcode.
 * Version: 1.0.0
 * Author: Daoxi Sun
 * Author URI: https://daoxisun.com/
 */

/** Organize the frontend part into a separate file */
require plugin_dir_path( __FILE__ ) . '/frontend-shortcode/frontend-shortcode.php';

add_action( 'admin_menu', 'slideshow_plugin' );

/**
 * Add the submenu page into Settings.
 */
function slideshow_plugin() {
	add_submenu_page(
		'options-general.php',
		'Slideshow Plugin Settings',
		'Slideshow Plugin',
		'administrator',
		'slideshow_settings',
		'slideshow_settings_page'
	);
}

/**
 * Offer the HTML of the plugin settings.
 */
function slideshow_settings_page(){?>
	<h1>Slideshow Plugin Settings Page</h1>
	<h3>
		Upload an image or select from the existing Media Library, or simply enter the URL of the image.<br/>
		Then you can add the image to the slideshow or reselect another one you like.
	</h3>
	<div id="selected-image-container" class="hidden">
	<img src="" alt="" title="" />
	</div>

	<form>
	<div>
	<input type="submit" id="select-new-image" value="Select/Upload Image" />
	<input type="submit" id="add-new-image" value="Add to Slideshow" />
	<input type="submit" id="remove-new-image" value="Choose Another" />
	</div>
	<input type="text" id="add-image-url" name="add-image-url" value="" />
	</form>
	<h1>Slideshow Images List</h1>
	<h3>
		The added images in the slideshow will be displayed below, note that you can drag and drop to rearrange their orders.
	</h3>
	<form type="post">
	<input type="hidden" name="action" value="slideshow_update_ajax">
	<ul id="slideshow-image-list" style="list-style: none;">
	</ul>
	<input type="submit" id="save-slidershow-images-ajax" value="Save Your Selection" >
	</form>

	<?php
}

add_action( 'admin_enqueue_scripts', 'enqueue_scripts' );

/**
 * Enqueue various scripts and styles.
 */
function enqueue_scripts() {
	// Prepare to use media API.
	wp_enqueue_media();

	wp_enqueue_script(
		'js_script_1',
		plugin_dir_url( __FILE__ ) . 'admin-side/js/admin-side.js',
		// add both jQuery and jQuery UI Sortable as registered script handles (dependencies).
		array( 'jquery', 'jquery-ui-sortable' ),
		true,
		false
	);

	$slideshow_data_images_urls = get_option( 'slideshow_images_urls' );
	// pass the variable to the enqueued JavaScript.
	wp_localize_script( 'js_script_1', 'slideshow_data_images_urls_js', $slideshow_data_images_urls );

	wp_enqueue_style(
		'css_script_1',
		plugin_dir_url( __FILE__ ) . 'admin-side/css/admin-side.css',
		array(),
		true,
		'all'
	);

}

add_action( 'wp_ajax_my_action', 'save_slideshow_images_ajax' );

/**
 * Save the list of image URLs to the database.
 */
function save_slideshow_images_ajax() {

	global $wpdb; // get access to the database.
	$saved_nonce = wp_create_nonce();
	if ( isset( $_POST ['slideshowImagesUrls'] ) && wp_verify_nonce( $saved_nonce ) ) {
		update_option( 'slideshow_images_urls', $_POST ['slideshowImagesUrls'] );
	}

	wp_die(); // terminate immediately and return a proper response.

}
