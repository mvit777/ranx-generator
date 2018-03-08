<?php

namespace Ranx\Generator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Finder\Finder;
use Ranx\Generator\Model\ModuleGenerator;

class ModuleGeneratorCommand extends Command
{
	private $generator;
	
	public function __construct(ModuleGenerator $generator, Finder $finder){
		$this->generator = $generator;
		$this->finder = $finder;
		parent::__construct();
	}
	
	protected function configure(){
		$this->setName('ranx:generate:module')
            ->setDescription('A command line extension for module skeleton generation. 
            				   Expects a Vendor/ModuleName string as required argument.
            				   Accepts a path to a config file as optional argument')
			->addArgument('name',InputArgument::REQUIRED,"provide a Vendor/ModuleName");
		return parent::configure();
	}
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$name = $input->getArgument('name');
		$this->finder->depth('== 0');
		$config_dir = dirname(dirname(__DIR__)).'/Model/res/configs';
		$this->finder->files()->in($config_dir);
		
		$choices = array();
		foreach ($this->finder as $file):
		    if(strstr($file->getRelativePathName(), 'module')):
		    	$choices[] = $file->getRelativePathname();
			endif;
		endforeach;
		$helper = $this->getHelper('question');
	    $question = new ChoiceQuestion(
	        'Please select a config file (defaults to 0, CTRL+C to abort)',
	        $choices,
	        '0'
	    );
		$question->setErrorMessage('Choice %s is invalid.');
		$output->writeln("Config files are placed in $config_dir");
		$config_file = $helper->ask($input, $output, $question);
		$output->writeln("Start building module $name");
		$output->writeln("using config file $config_file");
		$output->writeln($this->generator->run($name, array('config_file'=>$config_file)));
	}
}
	