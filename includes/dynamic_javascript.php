<?php 
/*------------------------------------------------------------------
A "mostly" static page. We do however, have to supply one of the functions with a valid URL to where the search handler page lives. --------------------------------------------------------------------*/ 

/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; 
}

//header('Content-type: text/javascript'); 
include_once('LiveSearch.php'); 
$plugin_url = LiveSearch::get_plugin_url(); 
$search_handler = $plugin_url; 

//------------------------------------------------------------------
?> 
<script>
jQuery(document).ready(main); 

function main() {   
	// Create a div where we can dynamically send results   
	jQuery('#search-6').append('<div id="ajax_search_results_go_here"></div>');      
	// Listen for changes in our search field (<input id="s" >)   
	jQuery('#s').keyup(get_search_results);
} 

function get_search_results() {
	var search_query = jQuery('#s').val();
	if(search_query != "" && search_query.length > 2 ) {        
		jQuery.get("<?php print $search_handler; ?>", {s:search_query }, write_results_to_page);
	}    
	else    
	{       
		console.log('Search term empty or too short.');    
	} 
 } 

function write_results_to_page(data,status, xhr) {   
	if (status == "error") {
	 var msg = "Sorry but there was an error: ";       
		console.error(msg + xhr.status + " " + xhr.statusText);   
	 }  
	else   {      
		jQuery('#ajax_search_results_go_here').html(data);   
	} 
} 


</script>
