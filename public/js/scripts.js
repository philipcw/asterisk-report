
	$(document).ready(function() {
		$('.go-top').click(function(e) {
			e.preventDefault();
			
			$('html, body').animate({scrollTop: 0}, 500);
		});

		// Show/hide the footer button
		$(window).scroll(function() {
			if ($(this).scrollTop() > 800) {
				$('.go-top').fadeIn(200);
			} else {
				$('.go-top').fadeOut(200);
			}
		});
	});	