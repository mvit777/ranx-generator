<?php

namespace Ranx\Generator\Model;

class Publisher implements IGenerator{
	protected $binPath;
    protected $configPath;
	protected $repos;
	protected $commands;
	protected $message;
	protected $package;
	
	public function __construct(){
		$this->binPath = dirname(__DIR__).'/bin';
		$this->configPath = __DIR__.'/res/configs/publisher/config.php';
		$this->message = '';
	}
	
	public function run($path, array $configs = array()){
		require_once $this->configPath;
		$this->package = $path;
		if($publishing_options['repos']):
			chdir($this->binPath.'/'.$configs['res_type']);
			$this->repos = $publishing_options['repos'];
			$this->commands = $publishing_options['commands'];
			foreach($this->repos as $key => $value):
				$this->execute($key, $value);
			endforeach;
		endif;
		
		return $this->getMessage();
	}
	
	protected function execute($key ,$value){
		call_user_func_array(array($this, $key), array($key, $value));
	}
	
	protected function github($key, $value){
		$this->git($key, $value, true);
	}
	
	/*
	 * mymirror stopped working properly, trying to force bitbucket -- see res/configs/publisher/config.php
	 */
	protected function bitbucket($key, $value){
		$this->git('mymirror', $value, true);
	}
	
	protected function mymirror($key, $value){
		$this->git($key, $value, false);
	}
	
	protected function git($key, $value, $commit_master=false){
		$branch = $key;
		$this->message .= "Updating $key $value".PHP_EOL;
		$this->message .= shell_exec("git checkout master");
		if($commit_master==true):
			$this->message .= shell_exec("git status");
			$this->message .= shell_exec("git add .");
			$this->message .= shell_exec('git commit -m "publishing package '.$this->package .'" ');
		endif;
		$this->message .= shell_exec("git checkout $branch");
		$this->message .= shell_exec("git merge master");
		$cmd = $this->commands[$key];//typically git push -u origin master see -- res/pubopt/config.php for mirrors
		$this->message .= shell_exec($cmd);
		$this->message .= shell_exec("git checkout master");
		
	}
	
	public function getMessage(){
		return $this->message;
	}
}
