<?

$memory = array();

function get_username($id) {
	global $sql, $memory;
	
	$sql2 = clone $sql; // Need to! Not to conflict loops!
	
	if ( isset($memory[$id]) ) return $memory[$id];
	
	$sql2->db_Select("user", "user_name", "user_id='".$id."'");
	$row = $sql2->db_Fetch();
	$memory[$id] = $row['user_name'];
	return $row['user_name'];
}


function insert_page() {
	global $sql,$prefs;
	$i = array();
	
	$i['page_title'] = mysql_real_escape_string($_POST['title']);
	$i['page_content'] = mysql_real_escape_string($_POST['content']);
	
	$i['page_content'] = strip_tags($i['page_content'], $prefs['allowed_tags']);
	
	$i['page_active'] = 1;
	$i['page_author'] = USERID;
	$i['page_datestamp'] = time();
	
	# Unselect all other pages:
	
	$sql->db_Update("wiki", "page_active=0 WHERE page_title='".$i['page_title']."'");
	$sql->db_Insert("wiki",$i);
	
	Header("Location: ?page=" . $i['page_title'] );
}	


function check_perm() {
	global $prefs;
	if ($prefs['editor_class'] == "admin") {
		if (ADMIN) return True;
	}
	elseif ($prefs['editor_class'] == "users") {
		if (USER) return True;
	}
	elseif ($prefs['editor_class'] == "all") {
		return True;
	}
	elseif ($prefs['editor_class'] == "admin") {
		if (ADMIN) return True;
	}
	elseif (check_class($prefs['editor_class'])) {
		return True;
	}
	else
		return False;
}


function edit_box($value="") {
	$rows = (e_WYSIWYG) ? 15 : 10;
	$ret = "<textarea class='tbox' id='wiki_content' name='content' cols='70' rows='{$rows}' style='width:95%' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>$value</textarea>";
	return $ret;
}



function page_form($a) {
	global $prefs;
	
	if ( $prefs['mod_rewrite'] )
		$post_url = $prefs['mod_rewrite_prefix'];
	else 
		$post_url = "./";
		
	if (in_array('page_content',array_keys($a))) 
		$content = stripslashes($a['page_content']);
	else
		$content = "";
		
	if (in_array('page_title',array_keys($a))) 
		$title = $a['page_title'];
	else
		$title = "";
	
	
	
	return "
		<form action='".$post_url."' method='POST'>
			<p><label for='title'>".LAN_W_4.":</label> <input name='title' id='title' value='".$title."' /></p>
			<p>".edit_box($content)."</p>
			<p style='text-align: center;'><input type='submit' value='".LAN_W_5."' /></p>
		</form>
	";
}


function get_history($page) {
	global $sql;
	$r = "<script>
	function urldecode( str ) {
	    var ret = str;
	    ret = ret.replace(/\+/g, '%20');
	    ret = decodeURIComponent(ret);
	    ret = ret.toString();
	    return ret;
	}
	</script><ul>";
	$sql->db_Select("wiki", "*", "ORDER BY page_datestamp DESC", false);
	$gen = new convert;
	while( $row = $sql->db_Fetch() ) {
		$r .= "<li><a href='#' onclick=\"document.getElementById('wiki_content').value=urldecode('".urlencode(stripslashes($row['page_content']))."');\" >".$gen->convert_date($row['page_datestamp'])." by ".get_username($row['page_author'])."</a></li>";
	}
	return $r . "</ul>";
}

?>