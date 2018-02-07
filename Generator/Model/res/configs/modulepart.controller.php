<?php
//generate a basic module part with a controller
require_once 'module.default.all.php';

//make sure you dont ovewrite folders and files that should/could be already existing
foreach($folders as $key=>$value){
	if(!strstr($key, '/Block') && !strstr($key, '/Controller/') && $key != '/etc/frontend' &&  !strstr($key, '/view')):
		unset($folders[$key]);
	endif;
}

foreach ($files as $key => $value) {
	if(!strstr($key, '/Block/') && !strstr($key, '/Controller/') && !strstr($key,'/etc/') && !strstr($key,'/view/frontend')):
		unset($files[$key]);
	endif;
}

unset($files['/etc/di.xml']);
unset($files['/etc/frontend/events.xml']);
