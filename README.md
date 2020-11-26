# seobytes_livesearch
This is a Wordpress plugin that creates a live product search with product categories and product attributes filters.
The filters as well as the live results appear as a drop down under the search input field of the WordPress search widget.
The search widget is better placed in central position in the header.

You might need to modify the id of the search widget element in line 24 of /includes/dynamic_javascript.php and /includes/dynamic_javascript_advanced.php
Uncomment line 37 to 40 in /includes/LiveSearch.php to enqueue the plugin stylesheet. 
It is disabled by default to avoid having to load one more CSS file. You can copy the style in your template <head> element instead. 
  
This plugin is inspired by an example from WordPress 3 Plugin Development Essentials
