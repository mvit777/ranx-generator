<?php
require_once 'module.default.all.php';

foreach($folders as $key=>$value){
	if(!strstr($key, '/Observer') && !strstr($key,'/etc' )):
		unset($folders[$key]);
	endif;
}

foreach($files as $key=>$value):
	if($key != "/Observer/Observer.php" && $key !="/etc/frontend/events.xml"):
		unset($files[$key]);
	endif;
endforeach;
