/**
 * Mahan front-end behaviour.
 *
 * No jQuery: everything here is plain DOM work, wrapped in small modules that
 * each bail out when their markup is not on the page.
 */
( function () {
	'use strict';

	var data = window.mahanData || {};
	var i18n = data.i18n || {};

	/* ---------------------------------------------------------------- *
	 * Small helpers
	 * ---------------------------------------------------------------- */

	function $( selector, scope ) {
		return ( scope || document ).querySelector( selector );
	}

	function $$( selector, scope ) {
		return Array.prototype.slice.call( ( scope || document ).querySelectorAll( selector ) );
	}

	function on( element, type, handler, options ) {
		if ( element ) {
			element.addEventListener( type, handler, options );
		}
	}

	var PERSIAN_DIGITS = [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ];

	function toPersian( value ) {
		if ( ! data.persianDigits ) {
			return String( value );
		}

		return String( value ).replace( /\d/g, function ( digit ) {
			return PERSIAN_DIGITS[ digit ];
		} );
	}

	function post( action, body ) {
		var payload = new FormData();

		payload.append( 'action', action );
		payload.append( 'nonce', data.nonce );

		Object.keys( body || {} ).forEach( function ( key ) {
			payload.append( key, body[ key ] );
		} );

		return fetch( data.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: payload
		} ).then( function ( response ) {
			return response.json();
		} );
	}

	function debounce( fn, wait ) {
		var timer;

		return function () {
			var args = arguments;
			var self = this;

			clearTimeout( timer );
			timer = setTimeout( function () {
				fn.apply( self, args );
			}, wait );
		};
	}

	/* ---------------------------------------------------------------- *
	 * Panels: drawer, search overlay, category dropdown
	 * ---------------------------------------------------------------- */

	var Panels = ( function () {
		var open = null;

		function show( name ) {
			var panel = $( '[data-mahan-panel="' + name + '"]' );

			if ( ! panel ) {
				return;
			}

			hide();

			panel.hidden = false;
			document.body.classList.add( 'mahan-no-scroll' );
			open = panel;

			$$( '[data-mahan-open="' + name + '"]' ).forEach( function ( button ) {
				button.setAttribute( 'aria-expanded', 'true' );
			} );

			var focusTarget = $( '[data-mahan-search-focus]', panel ) || $( 'button, a, input', panel );

			if ( focusTarget ) {
				window.setTimeout( function () {
					focusTarget.focus();
				}, 60 );
			}
		}

		function hide() {
			if ( ! open ) {
				return;
			}

			open.hidden = true;
			open = null;
			document.body.classList.remove( 'mahan-no-scroll' );

			$$( '[data-mahan-open]' ).forEach( function ( button ) {
				button.setAttribute( 'aria-expanded', 'false' );
			} );
		}

		function init() {
			on( document, 'click', function ( event ) {
				var opener = event.target.closest( '[data-mahan-open]' );

				if ( opener ) {
					event.preventDefault();
					show( opener.getAttribute( 'data-mahan-open' ) );
					return;
				}

				if ( event.target.closest( '[data-mahan-close]' ) ) {
					event.preventDefault();
					hide();
				}
			} );

			on( document, 'keydown', function ( event ) {
				if ( 'Escape' === event.key ) {
					hide();
				}
			} );
		}

		return {
			init: init,
			hide: hide
		};
	}() );

	/* ---------------------------------------------------------------- *
	 * Sticky header
	 * ---------------------------------------------------------------- */

	function initStickyHeader() {
		var header = $( '[data-mahan-header]' );

		if ( ! header || ! document.body.classList.contains( 'mahan-has-sticky-header' ) ) {
			return;
		}

		var threshold = 80;
		var lastState = false;

		function update() {
			var stuck = window.scrollY > threshold;

			if ( stuck !== lastState ) {
				header.classList.toggle( 'is-stuck', stuck );
				header.classList.toggle( 'is-shrunk', stuck );
				lastState = stuck;
			}
		}

		update();
		window.addEventListener( 'scroll', update, { passive: true } );
	}

	/* ---------------------------------------------------------------- *
	 * Drawer submenu toggles
	 * ---------------------------------------------------------------- */

	function initMenuToggles() {
		on( document, 'click', function ( event ) {
			var toggle = event.target.closest( '.mahan-menu--drawer .mahan-menu__toggle' );

			if ( ! toggle ) {
				return;
			}

			event.preventDefault();

			var item = toggle.closest( '.mahan-menu__item' );
			var isOpen = item.classList.toggle( 'is-open' );

			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Category dropdown in the shop header
	 * ---------------------------------------------------------------- */

	function initToggles() {
		on( document, 'click', function ( event ) {
			var toggle = event.target.closest( '[data-mahan-toggle]' );

			if ( toggle ) {
				var name = toggle.getAttribute( 'data-mahan-toggle' );
				var panel = $( '[data-mahan-panel="' + name + '"]' );

				if ( panel ) {
					event.preventDefault();
					panel.hidden = ! panel.hidden;
					toggle.setAttribute( 'aria-expanded', panel.hidden ? 'false' : 'true' );
				}

				return;
			}

			// Close any open inline panel when clicking outside it.
			$$( '[data-mahan-panel="categories"]' ).forEach( function ( panel ) {
				if ( ! panel.hidden && ! event.target.closest( '[data-mahan-panel="categories"]' ) ) {
					panel.hidden = true;
				}
			} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Dark mode
	 * ---------------------------------------------------------------- */

	function initDarkMode() {
		var toggles = $$( '[data-mahan-dark-toggle]' );

		if ( ! toggles.length ) {
			return;
		}

		toggles.forEach( function ( toggle ) {
			on( toggle, 'click', function () {
				var isDark = document.documentElement.classList.toggle( 'mahan-dark' );

				try {
					window.localStorage.setItem( 'mahan-theme', isDark ? 'dark' : 'light' );
				} catch ( error ) {
					// Storage can be unavailable in private mode; the toggle still works for this page.
				}
			} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Back to top and reading progress
	 * ---------------------------------------------------------------- */

	function initScrollWidgets() {
		var toTop = $( '[data-mahan-to-top]' );
		var progress = $( '[data-mahan-read-progress] span' );

		if ( ! toTop && ! progress ) {
			return;
		}

		if ( toTop ) {
			on( toTop, 'click', function () {
				window.scrollTo( { top: 0, behavior: 'smooth' } );
			} );
		}

		function update() {
			if ( toTop ) {
				toTop.classList.toggle( 'is-visible', window.scrollY > 400 );
			}

			if ( progress ) {
				var height = document.documentElement.scrollHeight - window.innerHeight;
				var ratio = height > 0 ? ( window.scrollY / height ) * 100 : 0;

				progress.style.width = Math.min( 100, Math.max( 0, ratio ) ) + '%';
			}
		}

		update();
		window.addEventListener( 'scroll', update, { passive: true } );
	}

	/* ---------------------------------------------------------------- *
	 * Live search
	 * ---------------------------------------------------------------- */

	function initLiveSearch() {
		$$( '[data-mahan-live-search]' ).forEach( function ( form ) {
			var input = $( 'input[type="search"]', form );
			var results = $( '[data-mahan-search-results]', form );

			if ( ! input || ! results ) {
				return;
			}

			function render( items, moreUrl ) {
				if ( ! items.length ) {
					results.innerHTML = '<p class="mahan-search__empty">' + ( i18n.noResults || '' ) + '</p>';
					results.hidden = false;
					return;
				}

				var html = items.map( function ( item ) {
					var image = item.image
						? '<img src="' + item.image + '" alt="" loading="lazy" />'
						: '';
					var price = item.price
						? '<span class="mahan-search__result-price">' + item.price + '</span>'
						: '';

					return '<a class="mahan-search__result" href="' + item.url + '">' + image +
						'<span class="mahan-search__result-body">' +
						'<span class="mahan-search__result-title">' + item.title + '</span>' +
						price +
						'</span></a>';
				} ).join( '' );

				if ( moreUrl ) {
					html += '<a class="mahan-search__more" href="' + moreUrl + '">' + ( i18n.loadMore || '' ) + '</a>';
				}

				results.innerHTML = html;
				results.hidden = false;
			}

			var search = debounce( function () {
				var term = input.value.trim();

				if ( term.length < 3 ) {
					results.hidden = true;
					return;
				}

				results.innerHTML = '<p class="mahan-search__empty">' + ( i18n.loading || '' ) + '</p>';
				results.hidden = false;

				post( 'mahan_live_search', { term: term } ).then( function ( response ) {
					if ( response && response.success ) {
						render( response.data.items, response.data.moreUrl );
					}
				} ).catch( function () {
					results.hidden = true;
				} );
			}, 320 );

			on( input, 'input', search );

			on( document, 'click', function ( event ) {
				if ( ! form.contains( event.target ) ) {
					results.hidden = true;
				}
			} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Load more / infinite scroll
	 * ---------------------------------------------------------------- */

	function initLoadMore() {
		var wrapper = $( '[data-mahan-loadmore]' );
		var container = $( '[data-mahan-archive]' );

		if ( ! wrapper || ! container ) {
			return;
		}

		var button = $( '[data-mahan-loadmore-button]', wrapper );
		var page = parseInt( wrapper.getAttribute( 'data-page' ), 10 ) || 1;
		var max = parseInt( wrapper.getAttribute( 'data-max' ), 10 ) || 1;
		var busy = false;

		function load() {
			if ( busy || page >= max ) {
				return;
			}

			busy = true;
			button.classList.add( 'is-loading' );

			post( 'mahan_load_more', {
				page: page,
				perPage: wrapper.getAttribute( 'data-per-page' ),
				postType: wrapper.getAttribute( 'data-post-type' ),
				style: wrapper.getAttribute( 'data-style' ),
				term: wrapper.getAttribute( 'data-term' ),
				taxonomy: wrapper.getAttribute( 'data-taxonomy' )
			} ).then( function ( response ) {
				busy = false;
				button.classList.remove( 'is-loading' );

				if ( ! response || ! response.success ) {
					return;
				}

				container.insertAdjacentHTML( 'beforeend', response.data.html );
				page += 1;

				revealNewCards( container );

				if ( ! response.data.hasMore || page >= max ) {
					wrapper.remove();
				}
			} ).catch( function () {
				busy = false;
				button.classList.remove( 'is-loading' );
				$( '.mahan-loadmore__label', button ).textContent = i18n.error || '';
			} );
		}

		on( button, 'click', load );

		if ( 'infinite' === wrapper.getAttribute( 'data-mahan-loadmore' ) && 'IntersectionObserver' in window ) {
			var observer = new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						load();
					}
				} );
			}, { rootMargin: '300px' } );

			observer.observe( wrapper );
		}
	}

	/* ---------------------------------------------------------------- *
	 * Newsletter
	 * ---------------------------------------------------------------- */

	function initNewsletter() {
		$$( '[data-mahan-newsletter]' ).forEach( function ( form ) {
			on( form, 'submit', function ( event ) {
				event.preventDefault();

				var input = $( 'input[type="email"]', form );
				var button = $( 'button', form );
				var message = $( '.mahan-newsletter__message', form );

				if ( ! input || ! input.value.trim() ) {
					return;
				}

				button.classList.add( 'is-loading' );
				message.textContent = '';
				message.classList.remove( 'is-error' );

				post( 'mahan_newsletter', { email: input.value.trim() } ).then( function ( response ) {
					button.classList.remove( 'is-loading' );

					if ( response && response.success ) {
						message.textContent = response.data.message;
						input.value = '';
					} else {
						message.textContent = ( response && response.data && response.data.message ) || i18n.error;
						message.classList.add( 'is-error' );
					}
				} ).catch( function () {
					button.classList.remove( 'is-loading' );
					message.textContent = i18n.error || '';
					message.classList.add( 'is-error' );
				} );
			} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Copy to clipboard
	 * ---------------------------------------------------------------- */

	function initCopyButtons() {
		on( document, 'click', function ( event ) {
			var button = event.target.closest( '[data-mahan-copy]' );

			if ( ! button || ! navigator.clipboard ) {
				return;
			}

			navigator.clipboard.writeText( button.getAttribute( 'data-mahan-copy' ) ).then( function () {
				var original = button.getAttribute( 'aria-label' );

				button.setAttribute( 'aria-label', i18n.copied || '' );
				button.classList.add( 'is-copied' );

				window.setTimeout( function () {
					button.setAttribute( 'aria-label', original );
					button.classList.remove( 'is-copied' );
				}, 1800 );
			} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Tabs
	 * ---------------------------------------------------------------- */

	function initTabs() {
		$$( '[data-mahan-tabs]' ).forEach( function ( group ) {
			var tabs = $$( '[role="tab"]', group );
			var panels = $$( '[role="tabpanel"]', group );

			function select( index ) {
				tabs.forEach( function ( tab, position ) {
					var active = position === index;

					tab.setAttribute( 'aria-selected', active ? 'true' : 'false' );
					tab.setAttribute( 'tabindex', active ? '0' : '-1' );
				} );

				panels.forEach( function ( panel, position ) {
					panel.hidden = position !== index;
				} );
			}

			tabs.forEach( function ( tab, index ) {
				on( tab, 'click', function () {
					select( index );
				} );

				on( tab, 'keydown', function ( event ) {
					var next = null;

					// The layout is RTL, so ArrowLeft moves forward.
					if ( 'ArrowLeft' === event.key ) {
						next = ( index + 1 ) % tabs.length;
					} else if ( 'ArrowRight' === event.key ) {
						next = ( index - 1 + tabs.length ) % tabs.length;
					} else if ( 'Home' === event.key ) {
						next = 0;
					} else if ( 'End' === event.key ) {
						next = tabs.length - 1;
					}

					if ( null !== next ) {
						event.preventDefault();
						select( next );
						tabs[ next ].focus();
					}
				} );
			} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Carousel
	 * ---------------------------------------------------------------- */

	function initCarousels() {
		$$( '[data-mahan-carousel]' ).forEach( function ( root ) {
			var track = $( '[data-mahan-carousel-track]', root );

			if ( ! track ) {
				return;
			}

			var config = {};

			try {
				config = JSON.parse( root.getAttribute( 'data-mahan-carousel' ) ) || {};
			} catch ( error ) {
				config = {};
			}

			var slides = $$( '.mahan-carousel__slide', track );

			if ( ! slides.length ) {
				return;
			}

			var prev = $( '[data-mahan-carousel-prev]', root );
			var next = $( '[data-mahan-carousel-next]', root );
			var dotsWrap = $( '[data-mahan-carousel-dots]', root );
			var index = 0;
			var timer = null;

			function perView() {
				var width = window.innerWidth;

				if ( width <= 640 ) {
					return config.perViewMobile || 1;
				}

				if ( width <= 1024 ) {
					return config.perViewTablet || 2;
				}

				return config.perView || 3;
			}

			function pages() {
				return Math.max( 1, Math.ceil( slides.length / perView() ) );
			}

			function apply() {
				var visible = perView();

				root.style.setProperty( '--mahan-per-view', visible );

				var offset = index * 100;

				// RTL tracks move in the opposite direction.
				track.style.transform = 'translateX(' + ( config.rtl ? offset : -offset ) + '%)';

				if ( prev ) {
					prev.disabled = ! config.loop && 0 === index;
				}

				if ( next ) {
					next.disabled = ! config.loop && index >= pages() - 1;
				}

				$$( '.mahan-carousel__dot', dotsWrap ).forEach( function ( dot, position ) {
					dot.classList.toggle( 'is-active', position === index );
				} );
			}

			function go( target ) {
				var total = pages();

				if ( target < 0 ) {
					index = config.loop ? total - 1 : 0;
				} else if ( target >= total ) {
					index = config.loop ? 0 : total - 1;
				} else {
					index = target;
				}

				apply();
			}

			function buildDots() {
				if ( ! dotsWrap ) {
					return;
				}

				dotsWrap.innerHTML = '';

				for ( var i = 0; i < pages(); i++ ) {
					( function ( position ) {
						var dot = document.createElement( 'button' );

						dot.type = 'button';
						dot.className = 'mahan-carousel__dot';
						dot.setAttribute( 'aria-label', String( position + 1 ) );
						dot.addEventListener( 'click', function () {
							go( position );
							restart();
						} );

						dotsWrap.appendChild( dot );
					}( i ) );
				}
			}

			function restart() {
				window.clearInterval( timer );

				if ( config.autoplay && pages() > 1 ) {
					timer = window.setInterval( function () {
						go( index + 1 );
					}, config.interval || 4000 );
				}
			}

			on( prev, 'click', function () {
				go( index - 1 );
				restart();
			} );

			on( next, 'click', function () {
				go( index + 1 );
				restart();
			} );

			on( root, 'mouseenter', function () {
				window.clearInterval( timer );
			} );

			on( root, 'mouseleave', restart );

			// Touch support.
			var startX = 0;
			var moved = false;

			on( track, 'touchstart', function ( event ) {
				startX = event.touches[ 0 ].clientX;
				moved = false;
			}, { passive: true } );

			on( track, 'touchmove', function () {
				moved = true;
			}, { passive: true } );

			on( track, 'touchend', function ( event ) {
				if ( ! moved ) {
					return;
				}

				var delta = event.changedTouches[ 0 ].clientX - startX;

				if ( Math.abs( delta ) < 40 ) {
					return;
				}

				// Swiping right moves forward in an RTL carousel.
				go( index + ( ( delta > 0 ) === Boolean( config.rtl ) ? 1 : -1 ) );
				restart();
			} );

			window.addEventListener( 'resize', debounce( function () {
				index = Math.min( index, pages() - 1 );
				buildDots();
				apply();
			}, 200 ) );

			buildDots();
			apply();
			restart();
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Counters and progress bars
	 * ---------------------------------------------------------------- */

	function animateCounter( element ) {
		var target = parseFloat( element.getAttribute( 'data-mahan-counter' ) ) || 0;
		var duration = parseInt( element.getAttribute( 'data-duration' ), 10 ) || 1800;
		var output = $( '.mahan-counter__number', element ) || element;
		var start = null;
		var decimals = ( String( target ).split( '.' )[ 1 ] || '' ).length;

		function step( timestamp ) {
			if ( ! start ) {
				start = timestamp;
			}

			var progress = Math.min( 1, ( timestamp - start ) / duration );
			// Ease out so the number settles rather than stopping abruptly.
			var eased = 1 - Math.pow( 1 - progress, 3 );
			var value = ( target * eased ).toFixed( decimals );

			output.textContent = toPersian( Number( value ).toLocaleString( 'en-US' ) );

			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			}
		}

		window.requestAnimationFrame( step );
	}

	function initObservers() {
		var counters = $$( '[data-mahan-counter]' );
		var bars = $$( '[data-mahan-progress]' );
		var reveals = $$( '.mahan-reveal' );

		if ( ! ( 'IntersectionObserver' in window ) ) {
			counters.forEach( animateCounter );
			bars.forEach( function ( bar ) {
				bar.style.width = bar.getAttribute( 'data-mahan-progress' ) + '%';
			} );
			reveals.forEach( function ( element ) {
				element.classList.add( 'is-visible' );
			} );
			return;
		}

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) {
					return;
				}

				var element = entry.target;

				if ( element.hasAttribute( 'data-mahan-counter' ) ) {
					animateCounter( element );
				} else if ( element.hasAttribute( 'data-mahan-progress' ) ) {
					element.style.width = element.getAttribute( 'data-mahan-progress' ) + '%';
				} else {
					element.classList.add( 'is-visible' );
				}

				observer.unobserve( element );
			} );
		}, { threshold: 0.25 } );

		counters.concat( bars, reveals ).forEach( function ( element ) {
			observer.observe( element );
		} );
	}

	function revealNewCards( container ) {
		$$( '.mahan-card', container ).forEach( function ( card ) {
			card.classList.add( 'is-visible' );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Countdown
	 * ---------------------------------------------------------------- */

	function initCountdowns() {
		var elements = $$( '[data-mahan-countdown]' );

		if ( ! elements.length ) {
			return;
		}

		function pad( value ) {
			return toPersian( value < 10 ? '0' + value : String( value ) );
		}

		function tick() {
			elements.forEach( function ( element ) {
				var due = parseInt( element.getAttribute( 'data-mahan-countdown' ), 10 ) * 1000;
				var remaining = due - Date.now();

				if ( remaining <= 0 ) {
					if ( ! element.classList.contains( 'is-expired' ) ) {
						element.classList.add( 'is-expired' );
						element.insertAdjacentHTML(
							'beforeend',
							'<span class="mahan-countdown__expired">' + ( element.getAttribute( 'data-expired' ) || '' ) + '</span>'
						);
					}

					return;
				}

				var seconds = Math.floor( remaining / 1000 );
				var values = {
					days: Math.floor( seconds / 86400 ),
					hours: Math.floor( ( seconds % 86400 ) / 3600 ),
					minutes: Math.floor( ( seconds % 3600 ) / 60 ),
					seconds: seconds % 60
				};

				Object.keys( values ).forEach( function ( unit ) {
					var output = $( '[data-unit="' + unit + '"]', element );

					if ( output ) {
						output.textContent = pad( values[ unit ] );
					}
				} );
			} );
		}

		tick();
		window.setInterval( tick, 1000 );
	}

	/* ---------------------------------------------------------------- *
	 * Typewriter
	 * ---------------------------------------------------------------- */

	function initTypewriter() {
		$$( '[data-mahan-typewriter]' ).forEach( function ( element ) {
			var words;

			try {
				words = JSON.parse( element.getAttribute( 'data-mahan-typewriter' ) );
			} catch ( error ) {
				return;
			}

			if ( ! words || words.length < 2 ) {
				return;
			}

			var target = $( '[data-mahan-typewriter-target]', element );
			var speed = parseInt( element.getAttribute( 'data-speed' ), 10 ) || 90;
			var pause = parseInt( element.getAttribute( 'data-pause' ), 10 ) || 1600;
			var wordIndex = 0;
			var charIndex = words[ 0 ].length;
			var deleting = false;

			function step() {
				var word = words[ wordIndex ];

				if ( deleting ) {
					charIndex -= 1;
				} else {
					charIndex += 1;
				}

				target.textContent = word.substring( 0, charIndex );

				var delay = deleting ? speed / 2 : speed;

				if ( ! deleting && charIndex === word.length ) {
					deleting = true;
					delay = pause;
				} else if ( deleting && 0 === charIndex ) {
					deleting = false;
					wordIndex = ( wordIndex + 1 ) % words.length;
					delay = speed * 3;
				}

				window.setTimeout( step, delay );
			}

			window.setTimeout( step, pause );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Portfolio filter
	 * ---------------------------------------------------------------- */

	function initFilters() {
		$$( '.mahan-filter' ).forEach( function ( bar ) {
			var grid = bar.parentElement.querySelector( '[data-mahan-filter-grid]' );

			if ( ! grid ) {
				return;
			}

			$$( '[data-mahan-filter]', bar ).forEach( function ( button ) {
				on( button, 'click', function () {
					var term = button.getAttribute( 'data-mahan-filter' );

					$$( '[data-mahan-filter]', bar ).forEach( function ( other ) {
						other.classList.toggle( 'is-active', other === button );
					} );

					$$( '[data-terms]', grid ).forEach( function ( item ) {
						var terms = item.getAttribute( 'data-terms' ).split( ' ' );
						var show = '*' === term || terms.indexOf( term ) !== -1;

						item.classList.toggle( 'is-hidden', ! show );
					} );
				} );
			} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Before / after slider
	 * ---------------------------------------------------------------- */

	function initCompare() {
		$$( '[data-mahan-compare]' ).forEach( function ( root ) {
			var range = $( '[data-mahan-compare-range]', root );

			if ( ! range ) {
				return;
			}

			function update() {
				root.style.setProperty( '--mahan-compare-pos', range.value + '%' );
			}

			on( range, 'input', update );
			update();
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Lightbox and video popups
	 * ---------------------------------------------------------------- */

	function openOverlay( innerHtml, className ) {
		var overlay = document.createElement( 'div' );

		overlay.className = className;
		overlay.innerHTML = innerHtml +
			'<button type="button" class="mahan-lightbox__close" aria-label="close">&times;</button>';

		function close() {
			overlay.remove();
			document.body.classList.remove( 'mahan-no-scroll' );
			document.removeEventListener( 'keydown', onKey );
		}

		function onKey( event ) {
			if ( 'Escape' === event.key ) {
				close();
			}
		}

		overlay.addEventListener( 'click', function ( event ) {
			if ( event.target === overlay || event.target.closest( '.mahan-lightbox__close' ) ) {
				close();
			}
		} );

		document.addEventListener( 'keydown', onKey );
		document.body.appendChild( overlay );
		document.body.classList.add( 'mahan-no-scroll' );
	}

	function initLightbox() {
		on( document, 'click', function ( event ) {
			var link = event.target.closest( '[data-mahan-lightbox-item]' );

			if ( link ) {
				event.preventDefault();
				openOverlay( '<img src="' + link.getAttribute( 'href' ) + '" alt="" />', 'mahan-lightbox' );
				return;
			}

			var play = event.target.closest( '[data-mahan-video]' );

			if ( ! play ) {
				return;
			}

			event.preventDefault();

			var url = play.getAttribute( 'data-mahan-video' );
			var markup;

			if ( /\.(mp4|webm|ogg)(\?|$)/i.test( url ) ) {
				markup = '<video src="' + url + '" controls autoplay playsinline></video>';
			} else {
				markup = '<iframe src="' + url + '" allow="autoplay; fullscreen" allowfullscreen title="video"></iframe>';
			}

			openOverlay( markup, 'mahan-video-modal' );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * WooCommerce: wishlist, quick view, sticky cart
	 * ---------------------------------------------------------------- */

	function initWooCommerce() {
		if ( ! data.hasWoo ) {
			return;
		}

		on( document, 'click', function ( event ) {
			var wishlist = event.target.closest( '[data-mahan-wishlist]' );

			if ( wishlist ) {
				event.preventDefault();

				post( 'mahan_toggle_wishlist', { product: wishlist.getAttribute( 'data-mahan-wishlist' ) } )
					.then( function ( response ) {
						if ( ! response || ! response.success ) {
							return;
						}

						wishlist.classList.toggle( 'is-active', response.data.added );

						$$( '[data-mahan-wishlist-count]' ).forEach( function ( counter ) {
							counter.textContent = toPersian( response.data.count );
						} );
					} );

				return;
			}

			var quick = event.target.closest( '[data-mahan-quick-view]' );

			if ( ! quick ) {
				return;
			}

			event.preventDefault();

			var modal = $( '#mahan-quick-view' );
			var body = $( '[data-mahan-quick-view-body]', modal );

			if ( ! modal || ! body ) {
				return;
			}

			body.innerHTML = '<div class="mahan-spinner" aria-hidden="true"></div>';
			modal.hidden = false;
			document.body.classList.add( 'mahan-no-scroll' );

			post( 'mahan_quick_view', { product: quick.getAttribute( 'data-mahan-quick-view' ) } )
				.then( function ( response ) {
					body.innerHTML = ( response && response.success )
						? response.data.html
						: '<p class="mahan-search__empty">' + ( i18n.error || '' ) + '</p>';
				} )
				.catch( function () {
					body.innerHTML = '<p class="mahan-search__empty">' + ( i18n.error || '' ) + '</p>';
				} );
		} );

		on( document, 'click', function ( event ) {
			if ( event.target.closest( '#mahan-quick-view [data-mahan-close]' ) ) {
				var modal = $( '#mahan-quick-view' );

				if ( modal ) {
					modal.hidden = true;
					document.body.classList.remove( 'mahan-no-scroll' );
				}
			}

			var scrollTo = event.target.closest( '[data-mahan-scroll-to]' );

			if ( scrollTo ) {
				var target = document.querySelector( scrollTo.getAttribute( 'data-mahan-scroll-to' ) );

				if ( target ) {
					target.scrollIntoView( { behavior: 'smooth', block: 'center' } );
				}
			}
		} );

		initStickyCart();
	}

	function initStickyCart() {
		var bar = $( '[data-mahan-sticky-cart]' );
		var form = $( '.summary form.cart' ) || $( '.summary .price' );

		if ( ! bar || ! form ) {
			return;
		}

		if ( ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				bar.hidden = entry.isIntersecting || entry.boundingClientRect.top > 0;
			} );
		}, { threshold: 0 } );

		observer.observe( form );
	}

	/* ---------------------------------------------------------------- *
	 * Sticky sidebar
	 * ---------------------------------------------------------------- */

	function initStickySidebar() {
		var sidebar = $( '[data-mahan-sticky]' );

		if ( ! sidebar || ! data.stickySidebar || window.innerWidth < 1025 ) {
			return;
		}

		var inner = $( '.mahan-sidebar__inner', sidebar );

		if ( ! inner ) {
			return;
		}

		// Only stick when the sidebar is shorter than the viewport; a taller one
		// would hide its own bottom.
		if ( inner.offsetHeight < window.innerHeight - 140 ) {
			inner.style.position = 'sticky';
			inner.style.top = '110px';
		}
	}

	/* ---------------------------------------------------------------- *
	 * Preloader
	 * ---------------------------------------------------------------- */

	function initPreloader() {
		var preloader = $( '[data-mahan-preloader]' );

		if ( ! preloader ) {
			return;
		}

		window.addEventListener( 'load', function () {
			preloader.classList.add( 'is-done' );

			window.setTimeout( function () {
				preloader.remove();
			}, 400 );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Boot
	 * ---------------------------------------------------------------- */

	function boot() {
		Panels.init();
		initStickyHeader();
		initMenuToggles();
		initToggles();
		initDarkMode();
		initScrollWidgets();
		initLiveSearch();
		initLoadMore();
		initNewsletter();
		initCopyButtons();
		initTabs();
		initCarousels();
		initObservers();
		initCountdowns();
		initTypewriter();
		initFilters();
		initCompare();
		initLightbox();
		initWooCommerce();
		initStickySidebar();
		initPreloader();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}

	// Elementor re-renders elements in the editor preview, so re-run the modules.
	window.addEventListener( 'elementor/frontend/init', function () {
		if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
			window.elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function () {
				initCarousels();
				initTabs();
				initCountdowns();
				initCompare();
				initObservers();
			} );
		}
	} );
}() );
