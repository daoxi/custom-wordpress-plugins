// accept the dollar sign so that jQuery can be used
function getMediaUploader($) {
	let file_frame, json;

	// If an instance of file_frame already exists, then it can be opened, otherwise create it.
	if (undefined !== file_frame) {
		file_frame.open();
		return;
	}
	// If an file_frame instance does not exist, create it.
	// Define the settings of the Media Uploader.
	file_frame = wp.media.frames.file_frame = wp.media(
		{
			frame: "post",
			state: "insert",
			multiple: false, // only one file at a time
		}
	);

	// Event handler for what to do when an image is selected, it's attached to the insert event.
	file_frame.on(
		"insert",
		function () {
			// Read the JSON data returned from the Media Uploader
			json = file_frame.state().get( "selection" ).first().toJSON();

			// Check the URL of image to display
			if (0 > $.trim( json.url.length )) {
				return;
			}

			// Set the properties of the image and display it
			$( "#selected-image-container" )
			.children( "img" )
			.attr( "src", json.url )
			.attr( "alt", json.caption )
			.attr( "title", json.title )
			.show()
			.parent()
			.removeClass( "hidden" );

			// Store the image's URL into the user-visible field
			$( "#add-image-url" ).val( json.url );
		}
	);

	// Open the actual file_frame
	file_frame.open();
}

// Define the image list item's HTML format all in one place so that it's easier to maintain
// Elements are separated to make it easier to read
function addImageToList($, imageUrl) {
	if (imageUrl.length > 0) {
		let toAppend =
		'<li class="listed-list">' +
		'<img src="' +
		imageUrl +
		'" alt="slideshow image preview" class="listed-image"/>' +
		'<div class="listed-bottom-div">' +
		'<button type="button" onclick="return this.parentNode.parentNode.remove();" class="listed-remove-button">Remove</button>' +
		'<input type="text" value="' +
		imageUrl +
		'" readonly="readonly" class="listed-image-url"/>' +
		"</div>" +
		'<hr class="listed-divider-line">' +
		"</li>";

		$( "#slideshow-image-list" ).append( toAppend );
	}
}

(function ($) {
	$(
		function () {
			$( document ).ready(
				function ($) {
					// Use loop to dynamically add all the HTML elements for each slide
					let targetList = document.getElementById( "slideshow-image-list" );
					$( slideshow_data_images_urls_js ).each(
						function () {
							addImageToList( $, this );
						}
					);
				}
			);

			$( "#select-new-image" ).on(
				"click",
				function (evt) {
					// Stop the anchor's default behavior
					evt.preventDefault();
					// Display the media uploader
					getMediaUploader( $ );
				}
			);

			$( "#add-new-image" ).on(
				"click",
				function (evt) {
					// Stop the anchor's default behavior
					evt.preventDefault();
					// Add image to the slides list
					addImageToList( $, $( "#add-image-url" ).val() );
				}
			);

			$( "#remove-new-image" ).on(
				"click",
				function (evt) {
					// Stop the anchor's default behavior
					evt.preventDefault();
					// Clear input field
					$( "#add-image-url" ).val( "" );
					$( "#selected-image-container" ).addClass( "hidden" );
				}
			);

			$( "#save-slidershow-images-ajax" ).on(
				"click",
				function (evt) {
					// Stop the anchor's default behavior
					evt.preventDefault();

					// action name here will be used for wp_ajax_<action_name>
					let data = {
						action: "my_action",
						slideshowImagesUrls: [],
					};

					// Put data from the forms (each slide in the list gives an URL) into variable
					$( "#slideshow-image-list li input" ).each(
						function () {
							data.slideshowImagesUrls.push( $( this ).val() );
						}
					);

					// Send data to server
					jQuery.post(
						ajaxurl,
						data,
						function (response) {
							console.log( "server responded: " + response );
							alert( "Your slideshow has been saved." );
						}
					);
				}
			);

			// The following 2 jQuery functions use the jquery-ui-sortable to let users sort/rearrange individual list items
			$( "#slideshow-image-list" ).sortable(
				{
					placeholder: "sortable-highlighted-placeholder",
				}
			);
			$( "#slideshow-image-list" ).disableSelection();
		}
	);
})( jQuery );
