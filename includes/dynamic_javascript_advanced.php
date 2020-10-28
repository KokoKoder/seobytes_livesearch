<?php 
/*------------------------------------------------------------------
A "mostly" static page. We do however, have to supply one of the functions with a valid URL to where the search handler page lives. 
--------------------------------------------------------------------*/ 
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

$orderby = 'name';
$order = 'asc';
$hide_empty = false ;
$cat_args = array(
    'orderby'    => $orderby,
    'order'      => $order,
    'hide_empty' => $hide_empty,
);

?> 

<script defer>	
jQuery(document).ready(main); 
function main() {
	var wrapperStart='<div class="DisplaySearchResults" id="DisplaySearchResults" ><div class="dropdown-content "><!--button class="btn btn-black close-search" id="close-search" style="float:right">x</button--><div class="container"><div class="row">';
	var wrapperEnd='</div></div></div></div>';
	var filterColStart='<div class="col-xl-6 offset-xl-0 col-md-5 offset-md-1" id="search_filter_spot"><form  class="filtervar">';
	var filterColEnd='</form></div>';
	var resultColStart='<div class="col-xl-6 col-md-6" id="ajax_search_results_go_here">';
	var resultColEnd='</div>';
	var reset_cat='<input type="radio" class="catfilter" name="catfilter" value=""><label for="">Kaikki tuoteryhmät</label><br>';
	var reset_color='<input type="radio" class="catfilter" name="color" value=""><label for="">Kaikki värit</label><br>';
	var overFlowStart='<div class="tidyFilter">';
	var overFlowEnd='</div>';
	var catFilterTitle= '<b>Tuoteryhmän rajaus</b>';
	var catFilterTitle2= '<b>Värien rajaus</b><br>';
	var catFilterForm1='<?php $product_categories = get_terms( 'product_cat', $cat_args ); foreach ( $product_categories as $prod_category ) {printf( '<input type="radio" class="catfilter" name="catfilter" value="%2$s"><label for="%2$s">%2$s</label><br>',esc_url( get_category_link( $prod_category->term_id ) ),esc_html( $prod_category->name ));}?>';
	var FilterForm3='<?php $attribute_filters=wc_get_attribute_taxonomy_names();foreach ($attribute_filters as $at_filter){if($at_filter=='pa_color-swatches'){$name=$at_filter;$id=wc_attribute_taxonomy_id_by_name( $name );$filter_name=wc_get_attribute( $id )->name;?>'+reset_color+'<?php $terms = get_terms($at_filter);foreach ( $terms as $term ) {printf('<input type="radio" class="catfilter" name="color" value="%1$s"><label for="%1$s">%1$s</label><br>',esc_html($term->name),esc_html($filter_name));}}}?>';
	/*var FilterForm3='<?php $attribute_filters=wc_get_attribute_taxonomy_names();foreach ($attribute_filters as $at_filter){$name=$at_filter;$id=wc_attribute_taxonomy_id_by_name( $name );$filter_name=wc_get_attribute( $id )->name;echo '<b>'.$filter_name.'</b><br>';?>'+reset_color+'<?php $terms = get_terms($at_filter);foreach ( $terms as $term ) {printf('<input type="radio" class="catfilter" name="%2$s" value="%1$s"><label for="%1$s">%1$s</label><br>',esc_html($term->name),esc_html($filter_name));}}?>';*/
	var assemblingBlocks = wrapperStart+filterColStart+catFilterTitle+overFlowStart+reset_cat+catFilterForm1+overFlowEnd+catFilterTitle2+overFlowStart+FilterForm3+overFlowEnd+filterColEnd+resultColStart+resultColEnd+wrapperEnd;
	// Create a div where we can dynamically send results   
	jQuery('#live-search').append(assemblingBlocks);
	jQuery('#s').keyup(get_search_results);
	jQuery('.catfilter').click(get_search_results);
}
 
function get_filter_value() {
		var j = jQuery.noConflict();
		var radioValue = j("input[name='catfilter']:checked").val();
		if(radioValue){
			return (radioValue);
		};
}
function get_color_value() {
	
		var j = jQuery.noConflict();
		var radioValue = j("input[name='color']:checked").val();
		if(radioValue){
			return (radioValue);
		};
}
function get_search_results() { 
	var search_term = jQuery('#s').val();
	var search_cat = get_filter_value();
	var search_color = get_color_value();
	console.log(search_term+' '+search_cat)+' '+search_color;
	display_result_box();
	if(search_term != "" && search_term.length > 2 ) {        
		jQuery.get("<?php print $search_handler; ?>", {s:search_term ,category_name:search_cat,color:search_color}, write_results_to_page);
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
function display_result_box(){
	jQuery('.DisplaySearchResults').css('display', 'flex');
	//close on click
	jQuery( "#close-search" ).click(function() {
		jQuery( "#DisplaySearchResults" ).css('display', 'none');
	});
	//close on click outside the search result box
	jQuery(document).on('click', function(event) {
	if (!jQuery(event.target).closest('#DisplaySearchResults').length) {
		jQuery('.DisplaySearchResults').css('display', 'none');
	  }
	});
}

</script>

