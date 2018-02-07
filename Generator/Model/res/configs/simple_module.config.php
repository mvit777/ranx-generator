<?php
require_once 'default_module.config.php';

foreach($folders as $key=>$value){
	if(!strstr($key, '/etc')):
		unset($folders[$key]);
	endif;
}

