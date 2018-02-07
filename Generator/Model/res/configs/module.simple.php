<?php
require_once 'module.default.all.php';

foreach($folders as $key=>$value){
	if(!strstr($key, '/etc')):
		unset($folders[$key]);
	endif;
}
unset($folders['/etc/frontend']);
unset($folders['/etc/adminhtml']);
