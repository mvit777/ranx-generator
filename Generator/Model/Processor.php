<?php

namespace Ranx\Generator\Model;

class Processor implements IGenerator{
	/*
	 *@param string $path -- a path to some processor file in Model/res/processors
	 *@param array $configs -- must contain the $configs['replacers'] array to process
	 * 
	 * @return string $this->message -- a processed subtemplate
	 */
	public function run($path, array $configs=array()){
		require_once __DIR__.'/processors/'.$path;
		
		$replacers = $configs['replacers'];
		ob_start();
		$this->message = ob_get_contents();
		ob_end_clean();
		
		return $this->getMessage();
	}
	
	public function getMessage(){
		return $this->message;
	}
}
