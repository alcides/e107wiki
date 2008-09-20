<?

// Initial imports

if(!defined("e107_INIT")) {
	require_once("../../class2.php");
}
include_lan(e_PLUGIN.'wiki/languages/'.e_LANGUAGE.'.php');
require_once(e_PLUGIN.'wiki/preferences.php');
require_once(e_PLUGIN.'wiki/templates.php');
require_once(e_PLUGIN.'wiki/utils.php');


$has_page = False;

if ( isset($_POST['title']) ) {
	insert_page();
}

$elements = explode(".",$_SERVER['QUERY_STRING']);

if ( $elements[0] == "new" ) {
	$title = "New Page";
	$param = array();
	if ( count($elements) > 1 )
		$param['page_title'] = $elements[1];
	$content = "" . page_form($param);	
}

elseif ( $elements[0] == "edit" || $elements[0] == "revert" || $elements[0] == "delete") {
	$page = mysql_real_escape_string($elements[1]);
	$count = $sql->db_Select("wiki", "*", "page_id = '".$page."' and page_active=1", true);
	if ($count > 0) {
		$row = $sql->db_Fetch();
		$content = "" . page_form($row);
		
		if ($elements[0] == "revert") {
			$content .= get_history($page);
		}
		
		if ( $elements[0] == "delete" && ADMIN) {
			$sql->db_Delete("wiki", "page_title='".$row['page_title']."' ");
			Header("Location: ./");
		}
		
	}
}

elseif ( !isset( $_GET['page'] ) ) {
	
	// list of last 10 pages added or modified
	
	$title = LAN_W_3;
	$sql->db_Select("wiki", "*", "page_active = 1 ORDER BY page_datestamp DESC LIMIT 0,10", true);
	$content = make_links( $sql->db_getList() );
	
} else {
	
	

	$page = mysql_real_escape_string($_GET['page']);
	$count = $sql->db_Select("wiki", "*", "page_title = '".$page."' and page_active=1", true);
	
	if ( $count > 0 ) {
		// Page does exists
		$row = $sql->db_Fetch();
		$title = $row['page_title'];
		$content = make_content($row);
		$has_page = $row['page_id'];
		
	} elseif ( check_perm() ) {
		$title = "New Page";
		$content = "" . page_form(array());
	} else {
		// Page not found
		$title = LAN_W_1;
		$content = LAN_W_2;
	}
}

require_once(HEADERF);
$ns->tablerender($title,$content . make_footer($has_page));
require_once(FOOTERF);

?>