/*
 * Some site-wide js
 * Init call is at the tail
 */
 
ep = {}; // Elephant Parents namespace for functions and variables

ep.init = function() 
{ // Any functions to load with the page
	//ep.scale_images('.picture img', 120);
	//ep.scale_images('#ep-community .view-content img', 90);

}

ep.W3CDOM = (document.createElement && document.getElementsByTagName); // DOM support check for scripts

ep.scale_images = function(selector, max_width) 
{

	//Sets the max width, in pixels, for every image
	max_width = (max_width > 0) ? max_width : 120;
	//Sets the syntax for finding the images
	if(!selector) return;
	
	$(selector).each(function() {
		var width = $(this).width();
		var height = $(this).height();
		if (width > max_width) {
			//Set variables	for manipulation
			var ratio = (height / width );
			var new_width = max_width;
			var new_height = (new_width * ratio);
			
			//Shrink the image and add link to full-sized image
			$(this).height(new_height).width(new_width);
//return;
//			$(this).hover(function() {
				$(this).attr('title', 'This image has been scaled down. Click to view the original image.');
				$(this).css('cursor', 'pointer');
//				});
			$(this).click(function() {
				window.location = $(this).attr('src');
				});
		} //ends if statement
	} //ends each function
	);
}



// que them up
$(document).ready( ep.init );

