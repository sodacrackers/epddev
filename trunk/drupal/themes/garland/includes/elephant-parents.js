/*
 * Create an Elephant Parents namespace for functions and variables
 * Function loader is at the tail
 */
ep = {};

ep.scale_images = function() 
{
	//Sets the max width, in pixels, for every image
	var max_width = 120; 	
	//Sets the syntax for finding the images
	var selector = '.node .picture img'; 	
	
	$(selector).each(function(){
		var width = $(this).width();
		var height = $(this).height();
		if (width > max_width) {
			//Set variables	for manipulation
			var ratio = (height / width );
			var new_width = max_width;
			var new_height = (new_width * ratio);
			
			//Shrink the image and add link to full-sized image
			$(this).height(new_height).width(new_width);
			$(this).hover(function(){
				$(this).attr("title", "This image has been scaled down.  Click to view the original image.")
				$(this).css("cursor","pointer");
				});
			$(this).click(function(){
				window.location = $(this).attr("src");
				});
		} //ends if statement
	} //ends each function
	);
}



$(document).ready(function() {
	ep.scale_images();
});
