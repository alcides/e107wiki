<?

function make_content($row) {
	global $tp;
	$contents = utf8_decode(stripslashes($tp->toHTML($row['page_content'], true, 'body')));
	
	include_once("Textile.php");
	
	$textile = new Textile;
	$contents = $textile->process($contents);
	
	return $contents . "<p><small>".LAN_W_8." <a href='".e_HTTP."user.php?id.".$row['page_author']."'>".get_username($row['page_author'])."</a></small></p>";
}

function make_link($page) {
	global $prefs;
	if ($prefs['mod_rewrite'])
			return $prefs['mod_rewrite_prefix'].urlencode($page['page_title'])."'>".$page['page_title']."</a>";
	else 
		return "<a href='?page=".urlencode($page['page_title'])."'>".$page['page_title']."</a>";
}


function make_links($array)
{
	$output = "<ul>";
	foreach ($array as $key => $value) {
		$output .= "<li>".make_link($value)."</li>";
	}
	return $output . "</ul>";
}


function make_footer($has_page=True) {
	global $prefs;
	

	$new_page= "?new";
	$latest_pages = "?";
	$edit_page = "?edit.".$has_page;
	$revert_page = "?revert.".$has_page;
	$delete_page = "?delete.".$has_page;

	$footer = "<h4>Wiki</h4>
	<ul>
		<li><a href='".$latest_pages."'>".LAN_W_3."</a></li>
		<li><a href='".e_HTTP . "search.php?&t=wiki&adv=0'>".LAN_W_9."</a></li>
		
		";
		if (check_perm()) {
			$footer.="<li><a href='".$new_page."'>".LAN_W_6."</a></li>";
			if ( $has_page ) {
				$footer.="<li><a href='".$edit_page."'>".LAN_W_5."</a></li>";
				if (ADMIN) {
					$footer.="<li><a href='".$revert_page."'>".LAN_W_7."</a></li>";
					$footer.="<li><a href='".$delete_page."'>".LAN_W_10."</a></li>";
				}
			}
		}
		
		$footer .= "
	</ul>";
	
	return $footer;
}




?>