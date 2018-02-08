<?php

$parent_theme = 'Magento/blank'; //Magento/blank | Magento/luma
$title = 'Your module title'; //can be changed at runtime
$subpath = 'frontend'; //can be changed at runtime
$previewimg = 'preview.jpg';//change it in Model/res/pix to your own
$logo = "logo.png"; // same as above

$folders = [
  '/etc'				=>true,
  '/media'				=>true,
];

$files = [
 	// "/composer.json"						=> "default_root_composer.skel",
 	"/theme.xml"							=> "default_theme_xml.skel",
	"/LICENSE.txt"							=> "default_root_license.skel",
	"/LICENSE_AFL.txt"						=> "default_root_license_afl.skel",
	"/registration.php"						=> "default_root_registration.skel",
];

$local_replacers = [
    "@@parent_theme@@" => $parent_theme,
    "@@subpath@@" => $subpath,
    "@@title@@" => $title,
	"@@yourdi@@"		=> "",
	"@@copyright@@"		=> "put your copyright here",
];

