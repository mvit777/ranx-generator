<?php
namespace Ranx\Generator\Model;

trait ModuleTraits{
		
	public function getReplacers(){
		$replacers = array(
			'@@lowercasevendor@@'=>strtolower($this->vendor),
			'@@lowercasemodule@@'=>strtolower($this->module),
			'@@vendor@@'=>$this->vendor,
			'@@module@@'=>$this->module
		);
		
		return array_merge($replacers, $this->local_replacers);
	}
	
	/*
	 * Load module/modulepart config file and instantiate local members
	 * string $config_file
	 */
	protected function loadConfig($config_file){
		require_once $this->configPath.'configs/'.$config_file;
		$this->folders = $folders;
		$this->files = $files;
		$this->local_replacers = $local_replacers;
		$this->events = $events;
		$this->di = $di;
		$this->routes = $routes;//not used
	}
	
	protected function configure($path){
		$tokens = explode('/',trim($path));
		$errMsg = "Error $path does not conform to the required VendorName/ModuleName format";
		if(count($tokens) !==2):
			throw new \Exception($errMsg);
		endif;
		if(strlen($tokens[0]) < 1):
			throw new \Exception($errMsg." VendorName is not defined");
		endif;
		if(strlen($tokens[1]) < 1):
			throw new \Exception($errMsg." ModuleName is not defined");
		endif;
		$this->vendor = ucfirst($tokens[0]);
		$this->module = ucfirst($tokens[1]);
		$this->path = $this->vendor .'/'.$this->module;
	}
	
	protected function compileFolders($path, $folderConfigs){
		foreach($this->folders as $key=>$value):
			if($value):
				$this->message .= $this->folderGenerator->run($path.$key, $folderConfigs).PHP_EOL;
			endif;
		endforeach;
	}
	
	protected function compileFiles($path, $fileConfigs){
		foreach($this->files as $key=>$value):
			if($value):
				$fileConfigs['skel'] = $value;
				foreach($fileConfigs['replacers'] as $k=>$v):
					if(strstr($key, $k)):
						$key = str_replace($k, $v, $key);
					endif;
				endforeach;
				$this->message .= $this->fileGenerator->run($path.$key, $fileConfigs).PHP_EOL;
			endif;
		endforeach;
	}
	
	protected function normaliseString($str){
		$str = strtolower($str);
		$str = str_replace("_", "-", $str);
		
		return $str;
	}
}
