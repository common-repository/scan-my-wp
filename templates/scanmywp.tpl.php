<?php
/**
 * This is html of admin side
 *
 * @package Scan My WP
 */

$html       = '</form>';
$form_nonce = wp_nonce_field( 'smwp_scan_action', 'smwp_scan_nonce_field' );

if ( $msgbox['msg'] ) {
	$html .= <<<HTML
	<div class="notification {$is_message_warning}">
	  <button class="delete"></button>
	 {$msgbox['msg']}
	</div>

HTML;
}

$html .= <<<HTML

<div id="tab_header" class="tabs is-centered">
  <ul>
    <li class="is-active" data-option="1"><a><span class="icon is-small"><i class="fa fa-dashboard"></i></span> Dashboard</a></li>
    <li data-option="2"><a><span class="icon is-small"><i class="fa fa-list"></i></span> Vulnerabilities</a></li>
    <li data-option="3"><a><span class="icon is-small"><i class="fa fa-map-marker"></i></span> Enumeration</a></li>
    <!--<li data-option="4"><a>Detections</a></li>
    <li data-option="5"><a>Others</a></li>-->
  </ul>
</div>

<div  id="tab_container" class="bulma">
  <div class="container_item is-active" data-item="1">
  	<div class="column ">
	  	<div class="notification">
		  	
		  	Last scanned {$last_scanned}
		  	
	  		<div class="pull-right">
	  			
		  		<span class="tag is-warning" id="scan_status" style="margin-top: -3px;">...</span>
		  		<form method="POST" style="display: inline;">
		  			<input type="hidden" name="scanNow" value="1">
                                        {$form_nonce}
	  				<button type="submit" class="button is-primary"  name="scanNow" id="scan_now"  style="margin-top: -5px;">Scan Now</button>
	  			</form>
  			</div>
	  		
		</div>
  		
  	</div>
  	<div class="clearfix"></div>

    <div class="columns is-multiline">

		<div class="column">
		  <div class="box">
		    <div class="heading"><span class="icon is-small"><i class="fa fa-list"></i></span> Vulnerabilities</div>
		    <div class="title">{$vuln_count}</div>
		  </div>
		</div>
		<div class="column">
		  <div class="box">
		    <div class="heading"><span class="icon is-small"><i class="fa fa-map-marker"></i></span> Enumeration</div>
		    <div class="title">{$disc_count}</div>
		  </div>
		</div>
		<div class="column">
		  <div class="box">
		    <div class="heading"><span class="icon is-small"><i class="fa fa-search"></i></span> Detections</div>
		    <div class="title"><small>coming soon..</small></div>
		  </div>
		</div>
		<div class="column">
		  <div class="box">
		    <div class="heading">Others</div>
		    <div class="title"><small>coming soon..</small></div>
		  </div>
		</div>
	</div>
	<div class="column">
	
	<nav class="panel">
	<p class="panel-heading">
	    Last 5 scans
	  </p>
	<div class="panel-block">
	<table class="table is-striped is-fullwidth">
	<thead>
		<tr><th>Scan ID</th><th>Total Issues</th><th>Status</th><th>Started</th><th>Updated</th></tr>
	</thead>
HTML;
foreach ( $last_five_scans as $scan ) {
	$results = unserialize( $scan->results );

	$count_issues = is_array( $results ) ? count( $results ) : 0;
	$html        .= '<tr><td>' . $scan->scan_id . '</td><td>' . $count_issues . '</td><td>' . $scan->status . '</td><td>' . ScanMyWPTools::time_ago( $scan->created ) . '</td><td>' . ScanMyWPTools::time_ago( $scan->updated ) . '</td></tr>';
}

$html .= <<<HTML
	</table>
	</div>
	</nav>
	</div>
  </div>
  <div class="container_item" data-item="2"> 
    {$vuln_table}
  </div>
  <div class="container_item" data-item="3">
    {$disc_table}
  </div>
</div>
<form>

<script>
//variables init
var smwp_last_scan_id  = {$smwp_last_scan_id};
</script>

<?php 

HTML;

