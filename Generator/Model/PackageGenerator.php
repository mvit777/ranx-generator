<?php
namespace Ranx\Generator\Model;

use Ranx\Generator\Model\FileGenerator;

require_once 'ModuleTraits.php';

class PackageGenerator extends FileGenerator implements IGenerator{
	use ModuleTraits;
	private $validator ='validate_m2_package.php';
	private $skipValidation;
	
	public function run($path, array $configs=array()){
		$this->configure($path , $configs);
		$this->skipValidation = isset($configs['skip_validation']) ? $configs['skip_validation'] : 'no';
		$this->compileFile($path, $configs, '');
		if(is_object($configs['publisher'])):
			$this->message .= "starting publishing process...";
			$publisher = $configs['publisher'];
			$this->message .= $publisher->run($path, $configs);
		endif;
		
		return $this->getMessage();
	}
	
	protected function compileFile($path, $replacers, $skel){
		$currentDir = __DIR__;
		chdir($this->basePath.$replacers['res_type'].'/'.$this->vendor);
		
		$source_dir = $this->module;
		$normalisedName = $this->normaliseString($this->vendor).'_'.$this->normaliseString($this->module);
		$target_dir = $normalisedName;
		$this->filesystem->mkdir($target_dir, 0775);
		$this->filesystem->mirror($source_dir, $target_dir);
		$this->removeGit($target_dir);
		//read version from composer.json
		//$zipFile = $normalisedName.'-1.0.0.zip';
		$zipFile = $normalisedName.'.zip';
		exec("zip -r $zipFile $target_dir/ ");
		$this->filesystem->remove($target_dir);
		$this->message .= "I created zip file $zipFile".PHP_EOL;
		$destination_file = dirname($currentDir).'/bin/'.$replacers['res_type'].'/'.$zipFile;
		$this->filesystem->copy($zipFile, $destination_file);
		$this->filesystem->remove($zipFile);
		if($this->skipValidation=='no'):
			chdir(dirname($currentDir).'/bin/');
			$cmd = "php ".$this->validator." -d $destination_file";
			$this->message .= shell_exec($cmd);
		endif;
		
		$this->message .= "I saved package in $destination_file ".PHP_EOL;
		//$this->message .= "Check log to see if it passed validation".PHP_EOL;
	}
	
	protected function removeGit($parent_dir){
		if(is_dir($parent_dir.'/.git')):
			$this->filesystem->remove($parent_dir.'/.git');
			$this->message .= "I removed git folder".PHP_EOL;
		endif;
	}
	
}
