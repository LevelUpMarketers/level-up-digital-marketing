( function( $, l10n ) {
	'use strict';

	/**
	 * Delete old Data.
	 */
	const deleteOldData = () => {
		document.addEventListener( 'click', ( event ) => {
			const button = event.target.closest( '.totaltheme-demo-importer-remove-old-data-btn' );
			
			if ( ! button ) {
				return;
			}

			event.preventDefault();

			const confirmResult = confirm( button.getAttribute( 'data-confirm' ) );

			if ( true === confirmResult ) {
				document.querySelector( '.totaltheme-demo-importer-deleting-data' ).classList.remove( 'hidden' );
				button.classList.add( 'hidden' );
				$.ajax( {
					url: l10n.ajaxurl,
					type: 'get',
					data: {
						action: 'totaltheme_demo_importer_delete_imported_data',
						nonce: button.getAttribute( 'data-nonce' )
					},
					statusCode: {
						500: function() {
							document.querySelector( '.totaltheme-demo-importer-deleting-data' ).classList.add( 'hidden' );
							document.querySelector( '.totaltheme-demo-importer-deleted-data-error' ).classList.remove( 'hidden' );
						}
					},
					complete: function( data ) {
						let response = data.responseText;
						try {
							response = JSON.parse( response );
						} finally {
							// nada
						}
						if ( 'object' === typeof response && Object.hasOwn(response, 'result' ) && 'success' === response.result ) {
							document.querySelector( '.totaltheme-demo-importer-deleting-data' ).classList.add( 'hidden' );
							document.querySelector( '.totaltheme-demo-importer-deleted-data-results' ).classList.remove( 'hidden' );
							setTimeout( () => {
								document.querySelector( '.totaltheme-demo-importer-delete-old-data-notice' ).classList.add( 'hidden' );
								document.querySelector( '.totaltheme-demo-importer-remove-old-data-btn' ).classList.remove( 'hidden' );
								document.querySelector( '.totaltheme-demo-importer-deleting-data' ).classList.add( 'hidden' );
								document.querySelector( '.totaltheme-demo-importer-deleted-data-results' ).classList.add( 'hidden' );
							}, 1500 );
						} else {
							console.error( response );
						}
					}
				} );
			}
		} );
	};

	/**
	 * Refresh button.
	 */
	const refresh = () => {
		let isRefreshing = false;
		document.addEventListener( 'click', ( event ) => {
			if ( isRefreshing ) {
				return;
			}
			
			const button = event.target.closest( '.totaltheme-demo-importer-refresh-btn' );
			
			if ( ! button ) {
				return;
			}

			button.disabled = true;
			isRefreshing = true;
			document.querySelector( '.totaltheme-demo-importer__top' ).remove();
			document.querySelector( '.totaltheme-demo-importer-grid' ).remove();
			document.querySelectorAll( '.totaltheme-demo-importer-warning' ).forEach( warning => {
				warning.remove();
			} );
			document.querySelector( '.totaltheme-demo-importer-refresh-notice' ).classList.add( 'totaltheme-demo-importer-refresh-notice--visible' );

			var xhr = new XMLHttpRequest();
			var data = `action=totaltheme_demo_importer_refresh_list&nonce=${button.dataset.nonce}`;

			xhr.onload = function() {
				var status = JSON.parse( this.responseText );
				if ( 4 == xhr.readyState && 200 == xhr.status ) {
					if ( 1 === status ) {
						location.reload();
					}
				} else {
					location.reload();
				}
			};

			xhr.open( 'POST', l10n.ajaxurl, true );
			xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
			xhr.send( data );

			event.preventDefault();
		} );
	};

	/**
	 * Demo Import function class.
	 */
	const TotalThemeCore_Demo_Importer = {

		/**
		 * Import data object.
		 */
		importData: {},

		/**
		 * Check if currently loading a demo.
		 */
		isLoadingDemo: false,

		/**
		 * Check if the modal can be closed.
		 */
		allowPopupClosing: true,

		/**
		 * Initialize the Demo Importer.
		 */
		init: function() {
			const self = this;

			// When a screenshot is clicked, get the name of the corresponding demo
			// and load the corresponding content for the popup.
			$( '.totaltheme-demo-importer-grid-item__screenshot' ).click( function( event ) {
				event.preventDefault();

				if ( ! self.isLoadingDemo ) {
					self.isLoadingDemo = true;
					$( this ).find( '.totaltheme-demo-importer-spinner' ).addClass( 'wpex-visible-spinner' );
					self.loadDemo( $( this ).parents( '.totaltheme-demo-importer-grid-item' ).attr( 'data-demo' ) );
				}
			} );

			// Setup import data.
			self.importData = {
				mods: {
					input_name: 'totaltheme_demo_importer_mods_check',
					preloader: l10n.strings.importing_mods
				},
				widgets: {
					input_name: 'totaltheme_demo_importer_widgets_check',
					preloader: l10n.strings.importing_widgets
				},
				sliders: {
					input_name: 'totaltheme_demo_importer_sliders_check',
					preloader: l10n.strings.importing_sliders
				},
				// Must be added last so it runs last!
				xml: {
					input_name: 'totaltheme_demo_importer_xml_check',
					preloader: l10n.strings.importing_xml
				}
			}

			// Filter demos based on the search box text.
			$( '.totaltheme-demo-importer-filter__search' ).on('input', function() {
				var currentInput = $( this ).val().toLowerCase();
				$( '.totaltheme-demo-importer-grid-item:not(.hidden-by-select)' ).each(function() {
					if ( $(this).find('.totaltheme-demo-importer-grid-item__name > span').text().toLowerCase().indexOf(currentInput) > -1 ) {
						$(this).removeClass( 'hidden hidden-by-search' );
					} else {
						$(this).addClass( 'hidden hidden-by-search' );
					}
				} );
			} );

			// Filter demos based on the selected category.
			$( '.totaltheme-demo-importer-filter__categories select' ).on( 'change', function() {
				const selected = $( this ).val().toLowerCase();
				$( '.totaltheme-demo-importer-grid-item:not(.hidden-by-search)' ).each(function() {
					if ( 'all' === selected || $(this).data( 'categories' ).toLowerCase().indexOf(selected) > -1 ) {
						$(this).removeClass( 'hidden hidden-by-select' );
					} else {
						$(this).addClass( 'hidden hidden-by-select' );
					}
				} );
			} );

			/* Filter demos based on the selected builder.
			$( '.totaltheme-demo-importer-filter__builders select' ).on( 'change', function() {
				const selected = $( this ).val().toLowerCase();
				$( '.totaltheme-demo-importer-grid-item:not(.hidden-by-search)' ).each(function() {
					if ( 'all' === selected || $(this).data( '-builder' ).toLowerCase().indexOf(selected) > -1 ) {
						$(this).removeClass( 'hidden hidden-by-select' );
					} else {
						$(this).addClass( 'hidden hidden-by-select' );
					}
				} );
			} );*/

			// Clear filter.
			const clearBtn = document.querySelector( '.totaltheme-demo-importer-filter__clear' );
			if ( clearBtn ) {
				clearBtn.addEventListener( 'click', e => {
					$( '.totaltheme-demo-importer-grid-item' ).each(function() {
						$(this).removeClass( 'hidden hidden-by-search hidden-by-select' );
					} );
					$( '.totaltheme-demo-importer-filter__categories select' ).val( 'all' );
					$( '.totaltheme-demo-importer-filter__builders select' ).val( 'all' );
					$( '.totaltheme-demo-importer-filter__search' ).val( '' );
				} );
			}

		},

		/**
		 * Helper function for parsing a URL.
		 */
		lightURLParse: function( url ) {
			const urlArray = url.split( '?' )[1].split( '&' );
			const result = [];
			$.each( urlArray, function( index, element ) {
				const elementArray = element.split( '=' );
				result[ elementArray[ 0 ] ] = elementArray[ 1 ];
			} );
			return result;
		},

		/**
		 * Load demo modal window.
		 */
		loadDemo: function( name ) {
			var self = this;
			$.ajax( {
				url: l10n.ajaxurl,
				type: 'get',
				data: {
					action: 'totaltheme_demo_importer_get_selected',
					step: 'selected',
					demo: name,
					nonce: l10n.nonce
				},
				complete: function( data ) {
					self.initPopup( data );
					$( '.wpex-visible-spinner' ).removeClass( 'wpex-visible-spinner' );
				}
			} );
		},

		/**
		 * Launch the popup and populate it with the appropriate content for the selected demo.
		 */
		initPopup: function( data ) {
			var self = this;

			// attach the HTML
			$( data.responseText ).appendTo( $( '.totaltheme-demo-importer-selected-modal__content' ) );

			// Show the popup
			$( '.totaltheme-demo-importer-selected-modal' ).toggleClass( 'totaltheme-demo-importer-selected-modal--visible' );

			// When 'Next' is clicked, remove the 'notices' and show the 'content to import'.
			$( '.totaltheme-demo-importer-selected-next' ).click( function( event ) {
				event.preventDefault();

				if ( $( this ).hasClass( 'disabled' ) ) {
					return;
				}
				
				const $dataEl = $( '.totaltheme-demo-importer-selected-data' );
				if ( $dataEl ) {
					$dataEl.remove();
				}
				
				const $import = $( '.totaltheme-demo-importer-selected-form' );
				if ( $import ) {
					$import.removeClass( 'hidden' );
				}
			} );

			// Hide the popup when 'Close' is clicked.
			$( '.totaltheme-demo-importer-selected-modal__close, .totaltheme-demo-importer-abort' ).click( function( event ) {
				event.preventDefault();
				self.closePopup();
			} );

			// when the background behind the popup is clicked, close the popup.
			$( '.totaltheme-demo-importer-selected-modal' ).click( function( event ) {
				if ( $( event.target ).hasClass( 'totaltheme-demo-importer-selected-modal__inner' ) && self.allowPopupClosing === true ) {
					self.closePopup();
				}
			} );

			// Enable and disable the 'Import XML Attachments' checkbox based on
			// whether 'Import XML Data' is checked.
			$( '#totaltheme_demo_importer_xml_check' ).change( function() {
				if ( ! $( this ).is( ':checked' ) ) {
					$( '#totaltheme_demo_importer_xml_attachments_check' ).attr({ 'checked': false, 'disabled': 'disabled' } );
				} else {
					$( '#totaltheme_demo_importer_xml_attachments_check' ).attr({ 'checked': true }).removeAttr( 'disabled' );
				}
			} );

			$( '.totaltheme-demo-importer-selected-form input[type="checkbox"]' ).change( function() {
				let hasSelection = false;
				$( '.totaltheme-demo-importer-selected-form input[type="checkbox"]' ).each( function() {
					if ( $( this ).is( ':checked' ) ) {
						hasSelection = true;
						return;
					}
				} );
				if ( hasSelection ) {
					$( '.totaltheme-demo-importer-selected-confirm' ).removeAttr( 'disabled' );
				} else {
					$( '.totaltheme-demo-importer-selected-confirm' ).attr( 'disabled', 'disabled' );
				}
			} );

			// Handle on confim on click.
			$( '.totaltheme-demo-importer-selected-confirm' ).click( function( event ) {
				if ( true !== confirm( $(this).attr( 'data-confirm' ) ) ) {
					event.preventDefault();
				}
			} );

			// Handle on retry click.
			$( '.totaltheme-demo-importer-retry-btn' ).click( function( event ) {
				event.preventDefault();
				if ( ! $( '.totaltheme-demo-importer-retry-btn' ).parent().hasClass( 'hidden' ) ) {
					$( '.totaltheme-demo-importer-selected-status__failed' ).remove();
					$( '.totaltheme-demo-importer-selected-form' ).submit();
				}
			} );

			// Handle the submit action.
			$( '.totaltheme-demo-importer-selected-form' ).submit( function( event ) {
				event.preventDefault();

				// Define content to import;
				const contentToImport = [];

				// Iterate through the form's input fields and check which fields are selected
				// in order to determine what content will be imported.
				$( this ).find( 'input[type="checkbox"]' ).each( function() {
					if ( $( this ).is( ':checked' ) === true ) {
						contentToImport.push( $( this ).attr( 'name' ) );
					}
				} );

				// Hide the checkboxes and show the importing preloader.
				$( '.totaltheme-demo-importer-selected-form' ).addClass( 'hidden' );
				$( '.totaltheme-demo-importer-selected-loading' ).removeClass( 'hidden' );

				// Start importing the content.
				const demo = $( this ).find( 'input[name="demo"]' ).val();

				self.importContent( {
					demo: demo,
					nonce: $( this ).find( `input[name="totaltheme_demo_importer_import_${demo}_nonce"]` ).val(),
					contentToImport: contentToImport,
					xmlChecked: $( '#totaltheme_demo_importer_xml_check' ).is( ':checked' )
				} );
			} );

			// Handle the installation/activation of the plugin.
			$( '.totaltheme-demo-importer-activate-plugin-btn, .totaltheme-demo-importer-install-plugin-btn' ).click(function( event ) {
				event.preventDefault();

				if ( $( this ).hasClass( 'disabled' ) ) {
					return;
				}

				// Get a reference ot the link.
				const $link = $( this );
				const $pluginRow = $link.parents( '.totaltheme-demo-importer-selected-plugins__item' );
				const url = self.lightURLParse( $link.attr( 'href' ) );
				const $actionResult = $link.parents( '.totaltheme-demo-importer-selected-plugins__item' ).find( 'td:last-child' );
				let action;

				// Assign the appropriate AJAX action based on which link was clicked.
				if ( 'install-plugin' === url.action ) {
					action = 'totaltheme_demo_importer_install_plugin';
				} else if ( 'activate' === url.action ) {
					action = 'totaltheme_demo_importer_activate_plugin';
				}

				// Disable the installation/activation links for the other plugins.
				$( '.totaltheme-demo-importer-selected-plugins__item a' ).addClass( 'disabled' );

				var activationLimit,
					timerStart = Date.now(),
					preloaderDots = '',
					preloaderMessage = url.action === 'install-plugin' ? l10n.strings.installing_plugin : l10n.strings.activating_plugin,
					preloaderInterval = setInterval(function() {
						$actionResult.text( preloaderMessage + preloaderDots );
						preloaderDots = preloaderDots.length === 5 ? '' : preloaderDots + '.';
				}, 200);

				this.allowPopupClosing = false;

				// Tell the server to install and or activate the plugin.
				var ajaxRequest = $.ajax( {
					url: l10n.ajaxurl,
					type: 'post',
					data: {
						action: action,
						nonce: url._wpnonce,
						plugin_slug: url.plugin,
					},
					complete: function( data ) {
						clearInterval( preloaderInterval );
						clearTimeout( activationLimit );

						self.allowPopupClosing = true;

						// Display the result of the action.
						if ( '1' === data.responseText ) {
							$link.after( '<span>' + $link.text() + '</span>' ).remove();
							$actionResult.empty().html( l10n.strings.plugin_activated ).addClass( 'totaltheme-plugin-action-result--success' );
							$pluginRow.removeClass( 'totaltheme-demo-importer-selected-plugins__item' );
						} else if ( data.status === 500 ) {
							$actionResult.empty().text( l10n.strings.plugin_failed_activation ).addClass( 'totaltheme-plugin-action-result--failed' );
						} else if ( typeof data.responseText !== 'undefined' && data.responseText.indexOf( 'target' ) !== -1 ) {
							$actionResult.empty().html( data.responseText ).addClass( 'totaltheme-plugin-action-result--failed' );
						} else {
							$actionResult.empty().html( l10n.strings.plugin_failed_activation ).addClass( 'totaltheme-plugin-action-result--failed' );
						}

						// Re-enable all the links.
						$( '.totaltheme-demo-importer-selected-plugins__item .disabled' ).removeClass( 'disabled' );

						// If there are no required plugins left, re-enable the 'next' button
						if ( $( '.totaltheme-demo-importer-selected-plugins__item' ).length === 0 ) {
							$( '.totaltheme-demo-importer-selected-next' ).removeClass( 'disabled' );
						}

						const actionType = url.action === 'install-plugin' ? 'installed' : 'activated';

						console.log( 'The plugin was ' + actionType + ' in ' + ( ( Date.now() - timerStart ) / 1000 ).toString() + ' seconds.' );
					}
				} );

				// Set a time limit of 30 seconds for the activation process. If the plugin
				// doesn't activate in that interval, display an error message and allow the
				// user to try again.
				if ( url.action === 'activate' || url.action === 'install-plugin' ) {
					activationLimit = setTimeout(function() {

						// Abort the AJAX request.
						ajaxRequest.abort();

						// Allow the popup to be closed.
						self.allowPopupClosing = true;

						// Display an error message.
						$actionResult.empty().html( l10n.strings.plugin_failed_activation ).addClass( 'totaltheme-plugin-action-result--failed' );

						// Re-enable all the links.
						$( '.totaltheme-demo-importer-selected-plugins__item .disabled' ).removeClass( 'disabled' );
					}, 30000);
				}
			} );
		},

		/**
		 * Close the popup and remove the loaded HTML content.
		 */
		closePopup: function() {
			this.isLoadingDemo = false;

			$( '.totaltheme-demo-importer-selected-modal' ).removeClass( 'totaltheme-demo-importer-selected-modal--visible' );

			$( '.totaltheme-demo-importer-selected-modal__content' ).one( 'transitionend', function() {
				$( '.totaltheme-demo-importer-selected-modal__content' ).empty();
			} );
		},

		/**
		 * Recursive function that will import the selected content.
		 */
		importContent: function( importData ) {
			var self = this,
				currentContent,
				importingLimit,
				timerStart = Date.now(),
				ajaxData = {
					demo: importData.demo,
					nonce: importData.nonce
				};

			this.allowPopupClosing = false;

			// When all the selected content has been imported.
			if ( importData.contentToImport.length === 0 ) {

				// Show last status.
				$( '.totaltheme-demo-importer-selected-status' ).append( `<p class="totaltheme-demo-importer-selected-status__content">${l10n.strings.finishing_import}</p>` );

				// Notify the server that the importing process is complete &
				// run extra functions.
				$.ajax( {
					url: l10n.ajaxurl,
					type: 'post',
					data: {
						action: 'totaltheme_demo_importer_step',
						step: 'complete',
						demo: importData.demo,
						nonce: importData.nonce,
						run_extras: importData.xmlChecked
					},
					complete: function( data ) {
						$( '.totaltheme-demo-importer-selected-status__content' )
							.addClass( 'totaltheme-demo-importer-selected-status__complete' )
							.removeClass( 'totaltheme-demo-importer-selected-status__content' );
						$( '.totaltheme-demo-importer-delete-old-data-notice' ).removeClass( 'hidden' );
						$( '.totaltheme-demo-importer-selected-loading .totaltheme-demo-importer-selected__warning' ).addClass( 'hidden' );
						$( '.totaltheme-demo-importer-retry' ).addClass( 'hidden' );
						$( '.totaltheme-demo-importer-selected-complete' ).removeClass( 'hidden' );
					}
				} );

				this.allowPopupClosing = true;

				// Stop the recursive function.
				return;
			}

			// Iterate through the list of importable content in order to get some data for
			// the content that was selected to be imported.
			for ( var key in this.importData ) {

				// Check if the current item in the iteration is in the list of importable content
				var contentIndex = $.inArray( this.importData[ key ][ 'input_name' ], importData.contentToImport );

				// If it is.
				if ( contentIndex !== -1 ) {

					// Get a reference to the current content.
					currentContent = key;

					// Remove the current content from the list of remaining importable content.
					importData.contentToImport.splice( contentIndex, 1 );

					// Get the AJAX action name that corresponds to the current content.
					ajaxData.action = this.importData[key]['action'] || 'totaltheme_demo_importer_step';

					// Set action step.
					if ( 'totaltheme_demo_importer_step' === ajaxData.action ) {
						ajaxData.step = key;
					}

					// If the current content is 'XML Data' check if 'XML Attachments' is also selected
					// because they will need to be imported at the same time.
					if ( key === 'xml' ) {
						var xmlAttachmentsIndex = $.inArray( 'totaltheme_demo_importer_xml_attachments_check', importData.contentToImport );

						if ( xmlAttachmentsIndex !== -1 ) {
							importData.contentToImport.splice( xmlAttachmentsIndex, 1 );
							ajaxData.importXML = 'true';
						}
					}

					// After an item is found get out of the loop and execute the rest of the function.
					break;
				}
			}

			// Tell the user which content is currently being imported.
			$( '.totaltheme-demo-importer-selected-status' ).append( `<p class="totaltheme-demo-importer-selected-status__content">${this.importData[currentContent]['preloader'] }</p>` );

			// Tell the server to import the current content.
			var ajaxRequest = $.ajax( {
				url: l10n.ajaxurl,
				type: 'post',
				data: ajaxData,
				timeout: 0,
				complete: function( data ) {
					clearTimeout( importingLimit );
					let continueProcess = true;

					if ( data.status ) {
						console.log( 'Response status: ' + data.status );
					}

					// Check if the importing of the content was successful or if there was any error
					if ( data.status === 500 || data.status === 502 || data.status === 503 ) {

						$( '.totaltheme-demo-importer-selected-status__content' )
							.addClass( 'totaltheme-demo-importer-selected-status__failed' )
							.removeClass( 'totaltheme-demo-importer-selected-status__content' )
							.text( `${l10n.strings.content_importing_error} ${data.status}` );

						continueProcess = false; // stop importing if we run into a server error.

						$( '.totaltheme-demo-importer-retry' ).removeClass( 'hidden' );

					} else if ( -1 !== data.responseText.indexOf( 'success' ) ) {

						// Uncheck element so we know that process was successful.
						$( '.totaltheme-demo-importer-selected-form' ).find( `input[name="${self.importData[ currentContent ]['input_name']}"]` ).attr( 'checked', false );
						
						// Update status.
						$( '.totaltheme-demo-importer-selected-status__content' )
							.addClass( 'totaltheme-demo-importer-selected-status__complete' )
							.removeClass( 'totaltheme-demo-importer-selected-status__content' );
					} else {
						const errors = $.parseJSON( data.responseText );
						let errorMessage = '';

						// Iterate through the list of errors.
						for ( const error in errors ) {
							errorMessage += errors[ error ];
						}

						// Display the error message.
						$( '.totaltheme-demo-importer-selected-status__content' )
							.addClass( 'totaltheme-demo-importer-selected-status__failed' )
							.removeClass( 'totaltheme-demo-importer-selected-status__content' )
							.text( errorMessage );

						self.allowPopupClosing = true;
					}

					// Continue with the loading only if an important error was not encountered.
					if ( continueProcess === true ) {
						// Load the next content in the list.
						self.importContent( importData );
					} else {
						console.error( 'import aborted due to error' );
					}

					console.log( self.importData[ currentContent ]['preloader'] + ': ' + ( ( Date.now() - timerStart ) / 1000 ).toString() + ' seconds.' );
				}
			} );

			// Set a time limit of 25 minutes for the importing process.
			importingLimit = setTimeout( function() {
				ajaxRequest.abort();

				// Allow the popup to be closed.
				self.allowPopupClosing = true;
				$( '.totaltheme-demo-importer-selected-status__content' )
					.addClass( 'totaltheme-demo-importer-selected-' )
					.removeClass( 'totaltheme-demo-importer-selected-status__content' )
					.text( l10n.content_importing_error );
			}, 1500000 );
		}

	};

	/**
	 * Add events.
	 */
	$( document ).ready( function() {
		deleteOldData();
		refresh();
		TotalThemeCore_Demo_Importer.init();
	} );

} ) ( jQuery, totaltheme_demo_importer_params );