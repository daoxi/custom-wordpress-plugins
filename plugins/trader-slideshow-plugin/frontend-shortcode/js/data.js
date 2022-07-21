// Adding one slide/image to the slideshow
function addImageToSlideshow($, imageUrl) {
	let toAppend =
	'<img class="mySlides" src="' +
	imageUrl +
	'" style="display = block; width:100%; height:30rem; object-fit: cover; ">';
	$( "#slideshow-images-container" ).append( toAppend );
}

// Adding the respective button/dot for each image in the slide show
function addDotToSlideshow($, index) {
	let toAppend =
	'<span class="w3-badge demo w3-border w3-transparent w3-hover-white" style="margin=0.5rem;" onclick="currentDiv(' +
	index +
	')"></span>';
	$( "#slideshow-buttons" ).append( toAppend );
}

(function ($) {
	("use strict");

	$(
		function () {
			// Populates the slide images and their respective control buttons
			$( document ).ready(
				function ($) {
					// Remove the exsiting default images and buttons which were included to avoid errors from the slideshow script
					$( ".mySlides" ).remove();
					$( ".demo" ).remove();
					// This index is used to keep track of button's identity
					let index = 1;
					// Variable passed from PHP by wp_localize_script()
					$( slideshow_data_images_urls_js ).each(
						function () {
							if (this.length > 0) {
								  addImageToSlideshow( $, this );
								  addDotToSlideshow( $, index );
								  index += 1;
							}
						}
					);
					// Use the slideshow script to initialize the slideshow's state
					var slideIndex = 1;
					showDivs( slideIndex );
				}
			);
		}
	);
})( jQuery );
