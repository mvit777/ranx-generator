<?php

namespace Ranx\Generator\Model;

use Ranx\Generator\Model\BaseGenerator;
use Ranx\Generator\Model\ModuleGenerator;

class ThemeGenerator extends ModuleGenerator{
    protected $basePath='app/'; 
	
	public function run($path, array $configs = array()){
		if(!isset($configs['config_file'])):
			$configs['config_file'] = 'theme.simple.php';
		endif;
		$configs['res_type'] = 'design';
		$this->configure($path, $configs);
		$this->loadConfig($configs['config_file']);
		$this->buildFolders($configs);
		$this->buildFiles($configs);
		$this->themeDir = $this->getFullyQualifiedPath($configs['res_type']).$this->path;
		$this->copyFile(__DIR__.'/res/pix/'.$this->previewImg, $this->themeDir.'/media/preview.jpg');
		
		return $this->getMessage();
	}
}
