<?php
namespace Ranx\Generator\Model;

use Ranx\Generator\Model\BaseGenerator;
use Ranx\Generator\Model\FolderGenerator;
use Ranx\Generator\Model\FileGenerator;
use Ranx\Generator\Model\Processor;
use Symfony\Component\Filesystem\Filesystem;

require_once 'ModuleTraits.php';

class ModuleGenerator extends BaseGenerator implements IGenerator{
	use ModuleTraits;
	
	protected $folderGenerator;
	protected $fileGenerator;
	protected $filesystem;
	
	
	public function __construct(FolderGenerator $folderGenerator, FileGenerator $fileGenerator, Filesystem $filesystem){
		$this->folderGenerator = $folderGenerator;
		$this->fileGenerator = $fileGenerator;
		$this->filesystem = $filesystem;
		$this->configPath = 'res/';
	}
	
	/*
	 * @param string $path -- the module path <Vendor>/<ModuleName>
	 */
	public function run($path, array $configs=array()){
		if(!isset($configs['config_file'])):
			$configs['config_file'] = 'module.simple.php';
		endif;
		if(!isset($configs['res_type'])):
			$configs['res_type'] = 'code';
		endif;
		$this->configure($path, $configs);
		$this->loadConfig($configs['config_file']);
		if(strstr($configs['config_file'], 'modulepart')):
			$configs['skip_duplicates'] = 1;
		endif;
		$this->buildFolders($configs);
		$this->buildFiles($configs);
		
		return $this->getMessage();
	}
	
	protected function buildFolders($folderConfigs){
		
		//$path = $this->path;
		//$folderConfigs = array('res_type'=>'code');
		//vendor folder
		try{
			$subpath = isset($folderConfigs['subpath']) ? $folderConfigs['subpath'] : '';
			$this->message .= $this->folderGenerator->run($subpath.'/'.$this->vendor, $folderConfigs).PHP_EOL;
		}catch(\exception $e){
			$this->message .= $e->getMessage().' skipping....'.PHP_EOL;
		}
		//module folder
		$this->message .= $this->folderGenerator->run($this->path, $folderConfigs).PHP_EOL;
		
		//all other folders inside module
		$folderConfigs['replacers'] = $this->getReplacers();
		$this->compileFolders($this->path, $folderConfigs);
	}
	
	protected function buildFiles($fileConfigs){
		
		$replacers = $this->getReplacers();
		
		$fileConfigs['replacers'] = $replacers;
		if(!empty($this->processors)):
			$fileConfigs['replacers'] = $this->processors($replacers, $fileConfigs);
		endif;
		$this->compileFiles($this->path, $fileConfigs);
	}
	
	protected function processors($replacers, $fileConfigs){
		$processor = new Processor();
		foreach($replacers as $key=>$value):
			if(array_key_exists($value, $this->processors)):
				$value = $this->processors[$value]['processor_file'];
				$replacers[$key] = $processor->run($value, array('replacers'=>$replacers, 'processors'=>$this->processors));
			endif;
		endforeach;
		
		return $replacers;
	}
}
