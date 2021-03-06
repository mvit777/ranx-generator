<?php

namespace Ranx\Generator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Finder\Finder;
use Ranx\Generator\Model\ModulePartGenerator;

class ModulePartGeneratorCommand extends Command
{
	private $generator;
	
	public function __construct(ModulePartGenerator $generator, Finder $finder){
		$this->generator = $generator;
		$this->finder = $finder;
		parent::__construct();
	}
	
	protected function configure(){
		$this->setName('ranx:generate:modulepart')
            ->setDescription('Module partial skeleton generation. 
            				   Expects a Vendor/ModuleName string to an existing module 
            				   as required argument.')
			->addArgument('name',InputArgument::REQUIRED,"provide a Vendor/ModuleName");
		return parent::configure();
	}
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$name = $input->getArgument('name');
		$output->writeln("Start building part for module $name");
		
		$this->finder->depth('== 0');
		
		$config_dir = dirname(dirname(__DIR__)).'/Model/res/configs';
		$this->finder->files()->in($config_dir);
		
		$choices = array();
		foreach ($this->finder as $file):
			if(strstr($file->getRelativePathName(), 'modulepart.')):
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
		
		$output->writeln($this->generator->run($name, $configs = array('config_file'=>$config_file)));
		
	}
}
	