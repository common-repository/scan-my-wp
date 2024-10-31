<?php
/**
 * Two classes for table and time display
 *
 * @package Scan My WP
 */

class ScanMyWPTable {

	// Column count for th.
	private $cols;

	/**
	 * Display table tag
	 */
	public function start() {
		return '<table>';
	}

		/**
		 * Display ending table tag
		 */
	public function end() {
		return '</table>';
	}

		/**
		 * Display table tr and th
		 *
		 * @param arr $cols total columns.
		 */
	public function head( $cols = array() ) {
		$this->cols = $cols;
		echo '<tr>';
		foreach ( $this->cols as $col ) {
			echo '<th>' . $col . '</th>';
		}
		echo '</tr>';
	}

		/**
		 * Display the card details
		 *
		 * @param arr $row total rows array.
		 */
	public static function card( $row = array() ) {

		$current_version = $fixed_in = $references = '';

		if ( $row->current_version ) {
			$current_version = 'Version ' . $row->current_version;
		}

		if ( $row->fixed_in && $row->fixed_in !== '-' ) {
			$fixed_in = ' | <span class="is-success">Fixed in ' . $row->fixed_in . '</span>';
		}

		if ( $row->refs ) {
			$references = 'References ' . $row->refs;
		}

		$tool_used = '<div class="toolUsed pull-right">Found using : ' . $row->tool . '</div>';

		return '<div class="card card-bulma">
		  <header class="card-header">
		    <p class="card-header-title" style="display: block">
		      ' . $row->title . '<span class="pull-right grayed"> ' . $current_version . $fixed_in . '</span>
		    </p>
		    <!--<a href="#" class="card-header-icon" aria-label="more options">
		      <span class="icon">
		        <i class="fa fa-angle-down" aria-hidden="true"></i>
		      </span>
		    </a>-->
		  </header>
		  <div class="card-content">
		    <div class="content">
		      ' . $row->details . ' <br><br>
		      ' . $references . '
		      ' . $tool_used . '
		    </div>
		  </div>
		  <!--<footer class="card-footer">
		    <a href="#" class="card-footer-item"></a>
		    <a href="#" class="card-footer-item"></a>
		    <a href="#" class="card-footer-item">Hide</a>
		  </footer>-->
		</div>';

	}

}

/**
 * Date and time display class
 */

class ScanMyWPTools {

		/**
		 * Display the time of scan
		 *
		 * @param date $datetime time.
		 * @param bool $full full format or not.
		 */
	public static function time_ago( $datetime, $full = false ) {
		$now  = new DateTime();
		$ago  = new DateTime( $datetime );
		$diff = $now->diff( $ago );

		$diff->w  = floor( $diff->d / 7 );
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ( $string as $k => &$v ) {
			if ( $diff->$k ) {
				$v = $diff->$k . ' ' . $v . ( $diff->$k > 1 ? 's' : '' );
			} else {
				unset( $string[ $k ] );
			}
		}

		if ( ! $full ) {
			$string = array_slice( $string, 0, 1 );
		}
		return $string ? implode( ', ', $string ) . ' ago' : 'just now';
	}
}
