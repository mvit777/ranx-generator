<?php

namespace Ranx\Generator\Model;

use \Symfony\Component\Filesystem\Filesystem;

abstract class BaseGenerator implements IGenerator{
	/*@var string $message -- the output messagge */
	protected $message;
	
	/*@var Filesystem $filesystem */
	protected $filesystem;
	
	/*@var string $basePath -- the code/design root folder */
	protected $basePath;
	
	/*@var string $fullyQualifiedPath -- either app/code/Vendor/Module | app/code/Vendor/Theme */
	protected $fullyQualifiedPath;
	
	/*@var string $configPath -- the root relative path to the config folder --Ex. res/ */
	protected $configPath;
	
	/*@var string $vendor */
	protected $vendor;
	
	/*@var string $module */
	protected $module;
	
	/*@var array $folders -- the array of folder taken from config file */ 
	protected $folders;
	
	/*@var array $files -- the array of files taken from config file */
	protected $files;
	
	/*@var string $resourcePath -- the full path to the resource to be created */
	protected $resourcePath;
	
	/*@ var string $skelPath -- the full path to the template file */
	protected $skelPath; 
	
	
	public function __construct(Filesystem $filesystem){
		$this->filesystem = $filesystem;
		$this->basePath = 'app/';
	}
	
	public abstract function run($path, array $configs=array());
	
	public function getMessage(){
		return $this->message;
	}
	
	protected function checkConfigs($item_type, $path, array $configs = array()){
		switch($item_type):
			case 'file':
			case 'folder':
				if(!isset($configs['res_type'])):
					throw new \Exception("Error, please define a configs['res_type']. Either code or design");
				endif;
				if($configs['res_type'] != 'code' && $configs['res_type'] != 'design'):
					throw new \Exception($configs['res_type']." is not a valid type. Please define a configs['res_type']. Either code or design");
				endif;
				$this->fullyQualifiedPath = $this->getFullyQualifiedPath($configs['res_type']);
				$this->resourcePath = $this->fullyQualifiedPath.$path;
				
				if ($this->filesystem->exists($this->resourcePath)):
					//TODO: if file
		 			throw new \Exception("Error ".$this->resourcePath." already exists");
					//TODO: if folder log, unset folder key and skip
				endif;
			break;
			default:
				
			break;
		endswitch;
	}
	
	protected function getFullyQualifiedPath($res_type){
		if(!isset($this->fullyQualifiedPath) or empty($this->fullyQualifiedPath)):
			$this->fullyQualifiedPath = $this->basePath.strtolower($res_type).'/';
		endif;
			
		return $this->fullyQualifiedPath;
	}
	
	protected function getSkelPath($res_type){
		if(!isset($this->skelPath) or empty($this->skelPath)):
			$this->skelPath = 'Model/res/skel/'.$res_type.'/';
		endif;
			
		return $this->skelPath;
	}

	/*public function getFileSystem(){
		return $this->filesystem;
	}*/
}
