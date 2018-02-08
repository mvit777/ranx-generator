<?php

namespace Ranx\Generator\Model;

use Ranx\Generator\Model\BaseGenerator;
use Ranx\Generator\Model\ModuleGenerator;

class ModulePartGenerator extends ModuleGenerator implements IGenerator{
	
	public function run($path, array $configs=array()){
		if(!isset($configs['config_file'])):
			throw new \Exception("Error you must specify a config file");
		endif;
		return parent::run($path, $configs);
	}
	
	/*
	 * Some pre-compilation checks
	 * Read comments
	 */
	protected function buildFolders($folderConfigs){
	    //=============debug============
		/*echo $this->path;//Vendor/Module
		die();*/
		//==============fine==========
		//dir app/code/Vendor/Module must exist
		//$folderConfigs = array('res_type'=>'code');
		$modulePath = $this->folderGenerator->getFullyQualifiedPath($folderConfigs['res_type']).$this->path;
		if(!$this->folderGenerator->filesystem->exists($modulePath)):
			throw new \Exception("Error module $modulePath does not exists");
		endif;
		//dir app/code/Vendor/Module/etc must exist or must be present in $this->folders keys or in the folder
		if(!$this->fileGenerator->filesystem->exists($modulePath.'/etc/module.xml')):
			if(!$this->folderGenerator->filesystem->exists($modulePath.'/etc')):
				$this->folders['/etc'] = true;
			endif;
			if(!array_key_exists('/etc/module.xml', $this->files)):
				$this->files['/etc/module.xml'] = "default_etc_module.skel";
			endif;
		else:
			if(array_key_exists('/etc/module.xml', $this->files)):
				unset($this->files['/etc/module.xml']);
				$this->message .= "Warning /etc/module.xml already exists...not writing it".PHP_EOL;
			endif;	
		endif;
		//checks for other files that should be already there and put them in compile list if not
		$checks = array(
			"/composer.json"			=> "default_root_composer.skel",
			"/LICENSE.txt"				=> "default_root_license.skel",
			"/LICENSE_AFL.txt"			=> "default_root_license_afl.skel",
			"/registration.php"			=> "default_root_registration.skel",
		);
		foreach($checks as $key=>$value):
			if(!$this->fileGenerator->filesystem->exists($modulePath.$key)): 
				if(!array_key_exists($key, $this->files)):
					$this->files[$key] =  $value;
				endif;
			else:
				if(array_key_exists($key, $this->files)):
					unset($this->files[$key]);
					$this->message .= $modulePath.$key." already exists skipping";
				endif;
			endif;
		endforeach;
		
		$this->message .= $this->compileFolders($this->path, $folderConfigs);
	}
}
