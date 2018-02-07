<?php

namespace Ranx\Generator\Model;

use Ranx\Generator\Model\BaseGenerator;
use Ranx\Generator\Model\ModuleGenerator;

class ThemeGenerator extends ModuleGenerator{
		
	public function run($path, $configs){
		
		return $this->getMessage();
	}
}
