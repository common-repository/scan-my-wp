jQuery( document ).ready(
	function() {

		var $ = jQuery;

		// bulma tab
		$( '#tab_header ul li' ).click(
			function() {
				var number = $( this ).data( 'option' );
				$( '#tab_header ul li' ).removeClass( 'is-active' );
				$( this ).addClass( 'is-active' );
				$( '#tab_container .container_item' ).removeClass( 'is-active' );
				$( 'div[data-item="' + number + '"]' ).addClass( 'is-active' );
			}
		);

		if (smwp_last_scan_id) {
			// show the progress button.
			$( '#scan_status' ).show();
			$( '#scan_now' ).addClass( 'is-loading' );

			var intervalListener = setInterval(
				function() {
					$.get(
						'https://api.scanmywp.com/api.php?type=check&scan_id=' + smwp_last_scan_id,
						function(data) {
							console.log( data );
							if (data.status == "completed") {
								window.clearInterval( intervalListener );
								alert( "Scan is complete, click OK to see the results" );

								window.location.href = window.location.pathname + "?" + $.param( {'page' : 'scan-my-wp', 'check_results':'true'} )
							} else {
								if (data.status == 'new') {
									$( '#scan_status' ).html( 'in queue' );
								} else if (data.status == 'progress') {
									$( '#scan_status' ).html( 'scanning..' );
								} else {
									$( '#scan_status' ).html( data.status );
								}

							}
						}
					)
				},
				3000
			);

		}
	}
);
