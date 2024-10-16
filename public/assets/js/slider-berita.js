$(document).ready(function () {
	// $imagesCarousel.flickity();
	// $('.main-carousel').flickity({
	// 	// options
	// 	cellAlign: 'left',
	// 	contain: true
	// });

	$('.carousel-event').flickity({
		/* options, defaults listed */

		accessibility: false,
		/* enable keyboard navigation, pressing left & right keys */
		
		autoPlay: true,
		pauseAutoPlayOnHover: false,
		/* advances to the next cell
		if true, default is 3 seconds
		or set time between advances in milliseconds
		i.e. `autoPlay: 1000` will advance every 1 second */
		
		cellAlign: 'left',
		/* alignment of cells, 'center', 'left', or 'right'
		or a decimal 0-1, 0 is beginning (left) of container, 1 is end (right) */
		
		// cellSelector: '.topic-switcher__item',
		/* specify selector for cell elements */
		
		contain: true,
		/* will contain cells to container
		so no excess scroll at beginning or end
		has no effect if wrapAround is enabled */
		
		draggable: true,
		/* enables dragging & flickin
		freeScroll: false,
		enables content to be freely scrolled and flicked
		without aligning cells */
		
		friction: 0.2,
		/* smaller number = easier to flick farther */
		
		initialIndex: 0,
		/* zero-based index of the initial selected cell */
		
		lazyLoad: false,
		/* enable lazy-loading images
		set img data-flickity-lazyload="src.jpg"
		set to number to load images adjacent cells */
		
		percentPosition: true,
		/* sets positioning in percent values, rather than pixels
		Enable if items have percent widths
		Disable if items have pixel widths, like images */
		
		prevNextButtons: true,
		/* creates and enables buttons to click to previous & next cells */
		
		pageDots: false,
		/* create and enable page dots */
		
		resize: true,
		/* listens to window resize events to adjust size & positions */
		
		rightToLeft: false,
		/* enables right-to-left layout */
		
		setGallerySize: true,
		/* sets the height of gallery
		disable if gallery already has height set with CSS */
		
		watchCSS: false,
		/* watches the content of :after of the element
		activates if #element:after { content: 'flickity' }
		IE8 and Android 2.3 do not support watching :after
		set watch: 'fallbackOn' to enable for these browsers */
		
		wrapAround: false
		/* at end of cells, wraps-around to first for infinite scrolling */
	})
	$('.carousel-prestasi').flickity({
		/* options, defaults listed */
		
		accessibility: false,
		/* enable keyboard navigation, pressing left & right keys */
		
		autoPlay: true,
		pauseAutoPlayOnHover: false,
		/* advances to the next cell
		if true, default is 3 seconds
		or set time between advances in milliseconds
		i.e. `autoPlay: 1000` will advance every 1 second */
		
		cellAlign: 'center',
		/* alignment of cells, 'center', 'left', or 'right'
		or a decimal 0-1, 0 is beginning (left) of container, 1 is end (right) */
		
		// cellSelector: '.topic-switcher__item',
		/* specify selector for cell elements */
		
		contain: false,
		/* will contain cells to container
		so no excess scroll at beginning or end
		has no effect if wrapAround is enabled */
		
		draggable: true,
		/* enables dragging & flickin
		freeScroll: false,
		enables content to be freely scrolled and flicked
		without aligning cells */
		
		friction: 0.2,
		/* smaller number = easier to flick farther */
		
		initialIndex: 0,
		/* zero-based index of the initial selected cell */
		
		lazyLoad: false,
		/* enable lazy-loading images
		set img data-flickity-lazyload="src.jpg"
		set to number to load images adjacent cells */
		
		percentPosition: true,
		/* sets positioning in percent values, rather than pixels
		Enable if items have percent widths
		Disable if items have pixel widths, like images */
		
		prevNextButtons: false,
		/* creates and enables buttons to click to previous & next cells */
		
		pageDots: false,
		/* create and enable page dots */
		
		resize: true,
		/* listens to window resize events to adjust size & positions */
		
		rightToLeft: false,
		/* enables right-to-left layout */
		
		setGallerySize: true,
		/* sets the height of gallery
		disable if gallery already has height set with CSS */
		
		watchCSS: false,
		/* watches the content of :after of the element
		activates if #element:after { content: 'flickity' }
		IE8 and Android 2.3 do not support watching :after
		set watch: 'fallbackOn' to enable for these browsers */
		
		wrapAround: true
		/* at end of cells, wraps-around to first for infinite scrolling */
	});
	
	$('.carousel-berita').flickity({
		/* options, defaults listed */
		
		accessibility: false,
		/* enable keyboard navigation, pressing left & right keys */
		
		autoPlay: true,
		pauseAutoPlayOnHover: false,
		/* advances to the next cell
		if true, default is 3 seconds
		or set time between advances in milliseconds
		i.e. `autoPlay: 1000` will advance every 1 second */
		
		cellAlign: 'center',
		/* alignment of cells, 'center', 'left', or 'right'
		or a decimal 0-1, 0 is beginning (left) of container, 1 is end (right) */
		
		// cellSelector: '.topic-switcher__item',
		/* specify selector for cell elements */
		
		contain: false,
		/* will contain cells to container
		so no excess scroll at beginning or end
		has no effect if wrapAround is enabled */
		
		draggable: true,
		/* enables dragging & flickin
		freeScroll: false,
		enables content to be freely scrolled and flicked
		without aligning cells */
		
		friction: 0.2,
		/* smaller number = easier to flick farther */
		
		initialIndex: 0,
		/* zero-based index of the initial selected cell */
		
		lazyLoad: false,
		/* enable lazy-loading images
		set img data-flickity-lazyload="src.jpg"
		set to number to load images adjacent cells */
		
		percentPosition: true,
		/* sets positioning in percent values, rather than pixels
		Enable if items have percent widths
		Disable if items have pixel widths, like images */
		
		prevNextButtons: false,
		/* creates and enables buttons to click to previous & next cells */
		
		pageDots: false,
		/* create and enable page dots */
		
		resize: true,
		/* listens to window resize events to adjust size & positions */
		
		rightToLeft: false,
		/* enables right-to-left layout */
		
		setGallerySize: true,
		/* sets the height of gallery
		disable if gallery already has height set with CSS */
		
		watchCSS: false,
		/* watches the content of :after of the element
		activates if #element:after { content: 'flickity' }
		IE8 and Android 2.3 do not support watching :after
		set watch: 'fallbackOn' to enable for these browsers */
		
		wrapAround: true
		/* at end of cells, wraps-around to first for infinite scrolling */
	});
});