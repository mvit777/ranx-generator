<?php

namespace Ranx\Generator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Ranx\Generator\Model\PackageGenerator;
use Ranx\Generator\Model\Publisher;

class PackageGeneratorCommand extends Command{
	private $generator;
	private $publisher;
	
	public function __construct(PackageGenerator $generator, Publisher $publisher){
		$this->generator = $generator;
		$this->publisher = $publisher;
		parent::__construct();
	}
	
	protected function configure(){
		$this->setName('ranx:module:package')
            ->setDescription('A command line extension for packaging an existing module. 
            				   Expects a Vendor/ModuleName string as required argument.')
			->addArgument('name',InputArgument::REQUIRED,"provide a Vendor/ModuleName");
		return parent::configure();
	}
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$name = $input->getArgument('name');
		$helper = $this->getHelper('question');
		$choices = array('no','yes');
		
		$question = new ChoiceQuestion(
	        'Skip package validation? (defaults to 0, CTRL+C to abort)',
	        $choices,
	        '0'
	    );
		$question->setErrorMessage('Choice %s is invalid.');
		$skipValidation = $helper->ask($input, $output, $question);
		
		$question = new ChoiceQuestion(
	        'Publish package? (defaults to 0, CTRL+C to abort)',
	        $choices,
	        '0'
	    );
		$question->setErrorMessage('Choice %s is invalid.');
		$output->writeln("Publish options config file is placed in Model/res/configs/publisher/config.php");
		$publish = $helper->ask($input, $output, $question);
		$publisher = $publish == 'yes' ? $this->publisher : null;
		
		$output->writeln("Start building packgage for module $name");
		
		$configs = array('res_type'=>'code', 'publisher'=>$publisher, 'skip_validation'=>$skipValidation);
		$output->writeln($this->generator->run($name, $configs));
		if($skipValidation=='no'):
			$output->writeln("Check log to see if package passed validation");
		endif;
	}
}
