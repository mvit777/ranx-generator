<?php

namespace Ranx\Generator\Model;

use Ranx\Generator\Model\BaseGenerator;

class FileGenerator extends BaseGenerator implements IGenerator{
	
	public function run($path, array $configs=array()){
		
		$this->checkConfigs('file', $path, $configs);
		
		$tokens = explode("/", $this->resourcePath);
		$file = array_pop($tokens);
		$destinationFolder = implode("/", $tokens);
		
		if(!$this->filesystem->exists($destinationFolder)):
			$this->message = $destinationFolder." folder does not exists. Skipping creation of file $file";
		else:
			$skel = dirname(__DIR__).'/'.$this->getSkelPath($configs['res_type']).$configs['skel'];
			//if(!is_file($skel)):
			if(!$this->filesystem->exists($skel)):
				$this->message = "Skel file $skel does not exists. Skipping creation of file ".$this->resourcePath;
			else:
				$replacers = array();
				if(isset($configs['replacers'])):
					$replacers = is_array($configs['replacers']) ? $configs['replacers'] : array();
				endif;
				$this->compileFile($this->resourcePath, $replacers, $skel);
				$this->message =  "I created file ".$this->resourcePath;
			endif;
		endif;
		
		return $this->getMessage();
	}
	/*
	 * TODO: use $filesystem
	 */
	protected function compileFile($path, $replacers, $skel){
		$content = file_get_contents($skel);
		foreach($replacers as $key=>$value):
			if(strstr($content, $key)):
				$content = str_replace($key, $value, $content);
			endif;
		endforeach;
		$handle = fopen($path, 'w');
		fwrite($handle, $content);
		fclose($handle);
	}
}
	