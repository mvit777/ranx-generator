<?php
namespace Ranx\Generator\Model;

use Ranx\Generator\Model\BaseGenerator;

class FolderGenerator extends BaseGenerator implements IGenerator{
	
	/*
	 * @param string $path -- the folder you want to create
	 * @param array $configs -- with at least one key res_type set to either code or design
	 * 
	 * @return string $message | exception
	 */
	public function run($path, array $configs=array()){
		$this->checkConfigs('folder', $path, $configs);
		
	 	//TODO: use $filesystem
		try{
			mkdir($this->resourcePath);
		}catch(\exception $e){
			throw new \Exception("$this->resourcePath could not be created");
			
		}
		
		return "I created folder ".$this->resourcePath;
	}
	
}
