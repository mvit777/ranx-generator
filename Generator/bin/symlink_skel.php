<?php
/*
 * USAGE: cd to Generator/bin from terminal
 * php symlink_skel.php
 */
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/vendor/autoload.php';
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

$moduledir = dirname(__DIR__);
$codedir = $moduledir.'/Model/res/skel/code';
$designdir = $moduledir.'/Model/res/skel/design';
chdir($codedir);
$fs = new Filesystem();
$defaultFiles = array(
    'default_root_license.skel',
	'default_root_license_afl.skel',
	
);
foreach($defaultFiles as $defaultFile):
	//$res = shell_exec("ln -s $defaultFile $designdir");
	try{
		$res = $fs->symlink($codedir.'/'.$defaultFile, $designdir.'/'.$defaultFile);
		echo "I symlinked from code/ to design/ file $defaultFile".PHP_EOL;
	}catch(exception $e ){
		echo $e->getMessage().PHP_EOL;
	}
	
	
endforeach;
