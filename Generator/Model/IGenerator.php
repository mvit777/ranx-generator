<?php
namespace Ranx\Generator\Model;

interface IGenerator{
	
	public function run($path, array $configs = array());
	
	public function getMessage();
	
}
