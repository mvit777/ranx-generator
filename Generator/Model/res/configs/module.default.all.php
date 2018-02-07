<?php
//order creation matters in case of subfolders
$folders = [
 "/Api/" 					=> true,
 "/Block/"					=> true,
 "/Controller/"				=> true,
 "/Controller/Index"		=> true,
 "/Controller/Adminhtml"	=> true,
 "/Cron/"					=> true,
 "/docs/"					=> true,
 "/etc/"					=> true,
 "/etc/frontend"			=> true,
 "/etc/adminhtml"			=> true,
 "/Helper"					=> true,
 "/i18n"					=> true,
 "/Model"					=> true,
 "/Observer"				=> true,
 "/Plugin"					=> true,
 "/Test"					=> true,
 "/Setup"					=> true,
 "/Ui"						=> true,
 "/view"					=> true,
 "/view/adminhtml"			=> true,
 "/view/frontend"			=> true,
 "/view/frontend/css"		=> true,
 "/view/frontend/css/source" => true,
 "/view/frontend/layout"	=> true,
 "/view/frontend/templates"	=> true,
];

$files = [
	"/composer.json"						=> "default_root_composer.skel",
	"/LICENSE.txt"							=> "default_root_license.skel",
	"/LICENSE_AFL.txt"						=> "default_root_license_afl.skel",
	"/registration.php"						=> "default_root_registration.skel",
	"/Block/Index.php"						=> "default_block_index.skel",
	"/etc/module.xml"						=> "default_etc_module.skel",
	"/etc/di.xml"							=> "default_etc_di.skel", //aggiungere anche un config con il di per un modulo cli
	"/etc/frontend/routes.xml"				=> "default_etc_frontend_routes.skel",
	"/etc/frontend/events.xml"				=> "default_etc_frontend_events.skel",
	"/Controller/Index/Index.php" 			=> "default_controller_index.skel",
	"/view/frontend/css/source/module.less" => "empty_file.skel",
	"/view/frontend/layout/@@lowercasemodule@@_index_index.xml" => "default_view_frontend_layout_module_index.skel",
	"/view/frontend/templates/index.phtml" => "empty_file.skel",
];


//not implemented yet
//local replacers are indeed merged
$local_replacers = [
	"@@youroutes@@"		=>"",
	"@@yourevents@@" 	=> "",
	"@@yourdi@@"		=> "",
	"@@copyright@@"		=> "put your copyright here",
	"@@commanditems@@"	=> "",
];

$routes = [];

$events = [];

$di = [];

//spostato in Model/res/configs/publisher/config.php
//$package = [];//also see this page http://devdocs.magento.com/guides/v2.2/extension-dev-guide/package/package_module.html

