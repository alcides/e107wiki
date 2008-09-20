<?php

/*

	e107 Wiki Plugin
	(C) 2008 Alcides Fonseca.
	http://alcidesfonseca.com

*/

if (!defined('e107_INIT')) { exit; }

$eplug_name = 'Wiki';
$eplug_version = '1.0';
$eplug_author = 'Alcides Fonseca';
$eplug_url = 'http://alcidesfonseca.com';
$eplug_email = 'me@alcidesfonseca.com';
$eplug_description = 'A traditional wiki';
$eplug_compatible = 'e107v0.7+';
$eplug_readme = '';

$eplug_folder = "wiki";

$eplug_table_names = array("wiki");
$eplug_tables = array(
	"CREATE TABLE ".MPREFIX."wiki (
	page_id int(10) unsigned NOT NULL auto_increment,
	page_title varchar(250) NOT NULL default '',
	page_content text NOT NULL,
	page_datestamp int(10) unsigned NOT NULL default '0',
	page_author int(10) NOT NULL,
	page_active tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY  (page_id),
	KEY page_active (page_active)
	) TYPE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8;"
);



$eplug_done = 'Your wiki is now installed';

?>