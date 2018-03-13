<?php
namespace Ranx\Generator\Model;

trait ModuleTraits{
		
	public function getReplacers(){
		$replacers = array(
			'@@lowercasevendor@@'=>strtolower($this->vendor),
			'@@lowercasemodule@@'=>strtolower($this->module),
			'@@vendor@@'=>$this->vendor,
			'@@packagename@@' =>$this->getPackageName(),
			'@@module@@'=>$this->module,
			'@@date@@'=> date('l jS \of F Y h:i:s A'),
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
		$this->processors = isset($processors) && !empty($processors) ? $processors : array();
		/*$this->events = isset($events) ? $events : array() ;
		$this->di = isset($di) ? $di : array();
		$this->routes = isset($routes) ? $routes : array();//not used*/
		$this->previewImg = isset($previewimg) ? $previewimg : '';
	}
	
	/*
	 *@param string $path -- Vendor/Module, Vendor/Theme
	 *@param string $res_type -- code | design
	 * 
	 * @return mixed void | exception
	 */
	protected function configure($path, $configs){
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
		if($configs['res_type']=='code'):
			$this->path = $this->vendor .'/'.$this->module;
		else:
			//used by ThemeGenerator to determine frontend or adminhtml
			$this->path = $configs['subpath'].'/'.$this->vendor.'/'.$this->module;
		endif;
	}
	
	protected function compileFolders($path, $folderConfigs){
		foreach($this->folders as $key=>$value):
			if($value):
				foreach($folderConfigs['replacers'] as $k=>$v):
					if(strstr($key, $k)):
						$key = str_replace($k, $v, $key);
					endif;
				endforeach;
				
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
	
	protected function copyFile($source, $target){
		$this->filesystem->copy($source, $target);
		$this->message .= "I copied file $source to destination $target".PHP_EOL;
	}
	
	protected function normaliseString($str){
		$str = strtolower($str);
		$str = str_replace("_", "-", $str);
		
		return $str;
	}
	
	public function getPackageName(){
		return $this->normaliseString($this->vendor).'/'.$this->normaliseString($this->module);
	}
}
