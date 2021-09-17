/**
 * Lightbox jQuery file for images on reservation details page.
 */
jQuery( document ).ready( function( $ ) {
	/**
	 * When the image is clicked.
	 */
	$( document ).on( 'click', '.masonry-grid img', function() {
		$(".lightbox").fadeIn(300);
		$(".lightbox").append("<img src='" + $(this).attr("src") + "' alt='" + $(this).attr("alt") + "' />");
		$(".filter").css("background-image", "url(" + $(this).attr("src") + ")");
		/*$(".title").append("<h1>" + $(this).attr("alt") + "</h1>");*/
		$("html").css("overflow", "hidden");
		if ($(this).is(":last-child")) {
			$(".arrowr").css("display", "none");
			$(".arrowl").css("display", "block");
		} else if ($(this).is(":first-child")) {
			$(".arrowr").css("display", "block");
			$(".arrowl").css("display", "none");
		} else {
			$(".arrowr").css("display", "block");
			$(".arrowl").css("display", "block");
		}
	});

	/**
	 * Close the lightbox.
	 */
	$( document ).on( 'click', '.close', function() {
		$( '.lightbox' ).fadeOut(300);
		$( 'h1' ).remove();
		$( '.lightbox img' ).remove();
		$( 'html' ).css( 'overflow', 'auto' );
	} );

	/**
	 * Close the lightbox on when Esc. key is pressed.
	 */
	$( document ).on( 'keyup', function( evt ) {
		// Check if the Esc. key is pressed.
		if ( 27 === evt.keyCode ) {
			$( '.lightbox' ).fadeOut(300);
			$( '.lightbox img' ).remove();
			$( 'html' ).css( 'overflow', 'auto' );
		}
	} );

	/**
	 * When the right hand arrow is clicked.
	 */
	$( document ).on( 'click', '.arrowr', function() {
		var imgSrc = $(".lightbox img").attr("src");
		var search = $(".masonry-grid").find("img[src$='" + imgSrc + "']");
		var newImage = search.next().attr("src");
		/*$(".lightbox img").attr("src", search.next());*/
		$(".lightbox img").attr("src", newImage);
		$(".filter").css("background-image", "url(" + newImage + ")");

		if (!search.next().is(":last-child")) {
			$(".arrowl").css("display", "block");
		} else {
			$(".arrowr").css("display", "none");
		}
	} );

	/**
	 * When the left hand arrow is clicked.
	 */
	$(".arrowl").click(function() {
		var imgSrc = $(".lightbox img").attr("src");
		var search = $(".masonry-grid").find("img[src$='" + imgSrc + "']");
		var newImage = search.prev().attr("src");
		/*$(".lightbox img").attr("src", search.next());*/
		$(".lightbox img").attr("src", newImage);
		$(".filter").css("background-image", "url(" + newImage + ")");

		if (!search.prev().is(":first-child")) {
			$(".arrowr").css("display", "block");
		} else {
			$(".arrowl").css("display", "none");
		}
	});

});
