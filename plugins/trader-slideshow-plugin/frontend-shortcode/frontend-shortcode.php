<?php
/**
 * Frontend Shortcode
 *
 * @package Frontend_Shortcode
 */

add_action( 'wp_enqueue_scripts', 'enqueue_slideshow_scripts' );

/**
 * Used to enqueue the external JavaScript and CSS.
 */
function enqueue_slideshow_scripts() {
	wp_enqueue_script(
		'data-script',
		plugin_dir_url( __FILE__ ) . 'js/data.js',
		array( 'jquery' ),
		'1.0.0',
		false
	);

	$slideshow_data_images_urls = get_option( 'slideshow_images_urls' );
	// pass the variable to the enqueued JavaScript.
	wp_localize_script( 'data-script', 'slideshow_data_images_urls_js', $slideshow_data_images_urls );

	wp_enqueue_script(
		'slideshow-script',
		plugin_dir_url( __FILE__ ) . 'js/slideshow-script.js',
		array(),
		'1.0.0',
		true
	);

	// Enqueue the W3.CSS library.
	wp_enqueue_style(
		'w3_css',
		plugin_dir_url( __FILE__ ) . '../lib/w3css/w3.css',
		array(),
		'4.15'
	);
	// Enqueue the css file for handling the slideshow style.
	wp_enqueue_style(
		'slideshow_buttons_css',
		plugin_dir_url( __FILE__ ) . 'css/slideshow_buttons.css',
		array(),
		'1.0.0'
	);

}

add_shortcode( 'myawesomecar', 'my_slideshow' );

/**
 * To output the slideshow HTML code.
 */
function my_slideshow() {
	// Store the HTML output content.
	$content = '
		<div class="w3-content w3-display-container" style="max-width:800px" id="slideshow-images-container">
			<img class="mySlides" src="" style="width:100%" >
			<div class="w3-center w3-container w3-section w3-large w3-text-white w3-display-bottommiddle" id="slideshow-buttons" style="width:100%">
				<div class="w3-left w3-hover-text-khaki" onclick="plusDivs(-1)">&#10094;</div>
				<div class="w3-right w3-hover-text-khaki" onclick="plusDivs(1)">&#10095;</div>
				<span class="w3-badge demo w3-border w3-transparent w3-hover-white" onclick="currentDiv(1)"></span>
			</div>
		</div>
	';

	return $content;
}
