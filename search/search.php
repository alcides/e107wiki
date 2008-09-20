<?php

$advanced_where = "";

// The fields that will be returned by the SQL
$return_fields = "page_title, page_content";

// The fields that can be search for matches
$search_fields = array("page_title", "page_content");

// A weighting for the importance of finding a match in each of the search fields
$weights = array("1.5", "1");

// Message to be displayed when no matches found
$no_results = LAN_198;

// The SQL WHERE clause, if any
$where = "page_active=1 and".$advanced_where;

// The SQL ORDER BY columns as a keyed array
$order = array('page_title' => ASC);

// The table(s) to be searched
$table = "wiki";

// Perform the search
$ps = $sch->parsesearch($table, $return_fields, $search_fields, $weights, 
                        'search_wiki', $no_results, $where, $order);

// Assign the results to specific variables
$text .= $ps['text'];
$results = $ps['results'];

// A callback function (name is passed to the parsesearch() function above)
// It is passed a single row from the DB result set
function search_wiki($row) {

   	require_once(e_PLUGIN.'wiki/preferences.php');


   // Populate as many of the $res array keys as is sensible for the plugin

	$res['link'] = e_PLUGIN."wiki/?page=".urlencode($row['page_title']);

   
   $res['pre_title'] = "";
   $res['title'] = $row["page_title"] . "";
   $res['pre_summary'] = "";

	include_once(e_PLUGIN."wiki/Textile.php");
	
	$textile = new Textile;

   $res['summary'] = stripslashes($textile->process(stripslashes($row['page_content'])));
   $res['detail'] = "";
   return $res;
}
?>