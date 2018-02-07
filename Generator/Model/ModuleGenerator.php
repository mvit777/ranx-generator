<?php
namespace Ranx\Generator\Model;

use Ranx\Generator\Model\BaseGenerator;
use Ranx\Generator\Model\FolderGenerator;
use Ranx\Generator\Model\FileGenerator;

require_once 'ModuleTraits.php';

class ModuleGenerator extends BaseGenerator implements IGenerator{
	use ModuleTraits;
	
	protected $folderGenerator;
	protected $fileGenerator;
	
	
	public function __construct(FolderGenerator $folderGenerator, FileGenerator $fileGenerator){
		$this->folderGenerator = $folderGenerator;
		$this->fileGenerator = $fileGenerator;
		$this->configPath = 'res/';
	}
	
	/*
	 * @param string $path -- the module path <Vendor>/<ModuleName>
	 */
	public function run($path, array $configs=array()){
		if(!isset($configs['config_file'])):
			$configs['config_file'] = 'module.simple.php';
		endif;
		$this->configure($path);
		$this->loadConfig($configs['config_file']);
		
		$this->buildFolders();
		$this->buildFiles();
		
		return $this->getMessage();
	}
	
	protected function buildFolders(){
		//$path = $this->path;
		$folderConfigs = array('res_type'=>'code');
		try{
			$this->message .= $this->folderGenerator->run($this->vendor, $folderConfigs).PHP_EOL;
		}catch(\exception $e){
			$this->message .= $e->getMessage().' skipping....'.PHP_EOL;
		}
		$this->message .= $this->folderGenerator->run($this->path, $folderConfigs).PHP_EOL;
		
		$this->compileFolders($this->path, $folderConfigs);
	}
	
	protected function buildFiles(){
		
		$replacers = $this->getReplacers();
		
		if(array_key_exists("@@yourevents@@", $replacers) && !empty($this->events)):
			$replacers["@@yourevents@@"] = $this->buildEvents();
		endif;
		//same for di and routes
		$fileConfigs = array('res_type'=>'code', 'replacers'=>$replacers);
		
		$this->compileFiles($this->path, $fileConfigs);
	}
	
	private function buildEvents(){
		//load snippet build_events.php
		//return compiled string
	}
	
	private function buildRoutes(){
		
	}
	
	private function buildDi(){
		
	}
}
