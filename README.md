# Ranx/Generator
A command line extension for magento-2.2.2 ce to build theme/module boilerplate

## Features ##

- Generates files and folders for Magento 2 Module or Theme boilerplate
- Generation can be configured via plain php arrays
- Multiple configurations are possible, that means one can generate different types of modules 
  ranging from the very basic module/theme with just the bare minimum to a complete module/theme
- Partial generation is possible, so that elements can be added after first generation
- It can also optionally create a magento package out of the source files
- Validate it against [Magento official validator](https://github.com/magento/marketplace-tools) (bundled)
- Push it to one or more remote repos

## Installation ##

Use git to pull this repository or download it as a zip. 
Whatever method you choose your app/code folder must look like this
```
app/
	code/
		Ranx/
			Generator/
```
enable the module
```
bin/magento module:enable Ranx_Generator
bin/magento setup:upgrade
bin/magento setup:di:compile
```
## Available Commands ##
From the root of magento list all availabe commands and look for ranx entry
```
someuser@somepc bin/magento list
```
```
ranx:generate:module                       Module skeleton generation. 
                                           Expects a Vendor/ModuleName string 
                                           as required argument.
  ranx:generate:modulepart                 Module partial skeleton generation. 
                                           Expects a Vendor/ModuleName string to an existing module 
                                           as required argument.
  ranx:generate:package                    Packages an existing module or theme. 
                                           Expects a Vendor/ModuleOrThemeName string 
                                           to an existing module or theme as required argument.
  ranx:generate:theme                      Skeleton generation for a custom theme. 
                                           Expects a Vendor/ThemeName string to an existing theme
                                           as required argument.
```
## Usage ##
**ranx:generate:module vendor/module**
to generate a new module from the root of magento installation
```
someuser@somepc: bin/magento ranx:generate:module <vendorname>/<modulename>
```
it will ask you what config file you want to use

```
Config files are placed in /var/www/magento2/magento/app/code/Ranx/Generator/Model/res/configs
Please select a config file (defaults to 0, CTRL+C to abort)
  [0] module.simple.php
  [1] module.default.cli.php
  [2] modulepart.controller.php
  [3] module.default.all.php
 > 
```
> **Ouput** a new Vendor/Module folder in the app/code/ folder.
> If the Vendor does not already exists it will be created. If the Module folder 
> already exists generation will not take place

Choice 0 (module.simple.php) builds only a very basic module with only required files. 
Should you decide to add another element (Ex. a controller) without recreating the whole module 
you can use the modulepart command

**ranx:generate:modulepart vendor/module**

```
 bin/magento ranx:generate:modulepart Test/Ranx_catalog
```
> **Ouput** new module elements are added to an existing Module. If the new elements 
> already exists they will not be overwritten

which only shows config files for partial module generation. 
Currently there is only one config file of this kind, but you can add your own by simply 
extending from the module.default.all.php (more on this in later sections)

```
Start building part for module Test/Ranx_catalog
Config files are placed in /var/www/magento2/magento/app/code/Ranx/Generator/Model/res/configs
Please select a config file (defaults to 0, CTRL+C to abort)
  [0] modulepart.controller.php
 > 
```
You can invoke the creation of the skeleton of a custom theme with this command.

**ranx:generate:theme vendor/theme**
```
someuser@somepc bin/magento ranx:generate:theme <vendor>/<theme>
```
You can pick a config file (currently only one but you can add yours, again more in next sections)
```
Start building theme Test/Ranx_catalog
Config files are placed in /var/www/magento2/magento/app/code/Ranx/Generator/Model/res/configs
Please select a config file (defaults to 0, CTRL+C to abort)
  [0] theme.simple.php
 > 
```
> **Ouput** a new Vendor/Theme folder in the app/design/frontend or adminhtml.
> If the Vendor does not already exists it will be created. If the Theme folder 
> already exists generation will not take place

You will be prompted to decide wheter it is a frontend or admin template
```
Start building theme Test/Ranx_catalog
using config file theme.simple.php
Is this frontend or admin theme? (defaults to 0, CTRL+C to abort)
  [0] frontend
  [1] adminhtml
 > 

```

You can decide to make it extend from Luma or Blank default themes.
```
extend from blank or luma theme? (defaults to 0, CTRL+C to abort)
  [0] blank
  [1] luma
 > 
```
The last available command let you build a magento2 compliant package out of an already existing 
theme or module

**ranx:generate:package vendor/theme_or_module**

```
someuser@somepc: bin/magento ranx:generate:package <vendor>/<module_or_theme_name>
```
> **Output** a .zip package in the Generator/bin/code or design folder. Along the process 
> the package can be uploaded to some remote repository

It will prompt you to specify if it a theme or a module
```
Is this a module or a theme? (defaults to 0, CTRL+C to abort)
  [0] module
  [1] theme
 > 

```
If is a module you will be asked if you want to validate it against [Magento2 validator](https://github.com/magento/marketplace-tools)

```
Skip package validation? (defaults to 0, CTRL+C to abort)
  [0] no
  [1] yes
 > 
```
if it is a theme you will be asked if it is a frontend or admin theme
```
Is this frontend or admin theme? (defaults to 0, CTRL+C to abort)
  [0] frontend
  [1] adminhtml
 > 
```

and finally it will ask you if package has to be published to some remote repository
```
Publish options config file is placed in Model/res/configs/publisher/config.php
Publish package? (defaults to 0, CTRL+C to abort)
  [0] no
  [1] yes
 > 
```
## How it works ##
Generation is run against a config file of your choice. Config files reside in the Generator/Model/res/configs folder.
There you can find a bunch of already configured modules types.
These config files basically contains:
- an array of folder names and their location
- an array of files to generate, mapped to corresponding skel files that act as templates for the content of the generated files
- an array of local replacers (in addition to the default ones) which are substituted in the skel files
- an array of processors that help replacers substitution in some particular files (usually when some elements have to be repeated in a loop)

## Adding your config files ##
The **module.default.all.php** config file contains every possible file and folder (well not quite yet, but something like 90% atm).
The other config files simply include **[module.default.all.php](https://github.com/mvit777/ranx-generator/blob/master/Generator/Model/res/configs/module.default.all.php)** (or **module.simple.php**) and pop elements from those two arrays when needed.
So one does not need to replicate all the configurations from config file to config file but rather add/remove/adjust only those who are needed

Ex. **module.simple.php**
```
<?php
require_once 'module.default.all.php';//<<<<

foreach($folders as $key=>$value){
	if(!strstr($key, '/etc')):
		unset($folders[$key]);
	endif;
}
unset($folders['/etc/frontend']);
unset($folders['/etc/adminhtml']);
```
they can also push elements if needed

Ex. **module.default.cli.php**
```
<?php
require_once 'module.simple.php';//<<<<

$folders['/Console'] = true;
$folders['/Console/Command'] = true;

$files["/etc/di.xml"] = "climod_etc_di.skel";
```
At the moment only four types of modules' boilerplate and one theme boilerplate are available:
 -  [0] module.simple.php
 -  [1] module.default.cli.php
 -  [2] modulepart.controller.php
 -  [3] module.default.all.php
 
 - nr. 0 outputs a very basic module with all the required files and folders
 - nr. 1 does the same but add the Command folder and a di.xml file
 - nr. 2 add a Controller to an already existing module
 - nr. 3 output a module with all possible files and folders

The idea is to replicate all the library of sample modules that is hosted on [magento samples](https://github.com/magento/magento2-samples)

## Adding your own skel files ##
Files generation happens through the processing of templates files  (.skel) that reside in the **Generator/Model/res/skel folder**.
Placeholders in those files are replaced by a list of **replacers** configured in the config file and for some files with the help of **processors** (see next section).

As for config files you can add more skel to accomodate your generation needs.

## Adding Replacers and Processors ##
A typical skel file looks something like that

**Model/res/skel/default_controller_index.skel**
```
<?php
/**
 * @@copyright@@
 * See COPYING.txt for license details.
 */
namespace @@vendor@@\@@module@@\Controller\Index;

use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action{
		
	protected $resultPageFactory;
```
An array of default replacers gets generated automatically at module generation runtime
```
<?php
namespace Ranx\Generator\Model;
trait ModuleTraits{
		
	public function getReplacers(){
		$replacers = array(
			'@@lowercasevendor@@'=>strtolower($this->vendor),
			'@@lowercasemodule@@'=>strtolower($this->module),
			'@@vendor@@'=>$this->vendor,
			'@@packagename@@' =>$this->getPackageName(),
			'@@module@@'=>$this->module,
			'@@date@@'=> date('l jS \of F Y h:i:s A'),
		);
		
		return array_merge($replacers, $this->local_replacers);
	}
```
and it is merged with a local_replacers array which you can define in the config file
```
$local_replacers = [
	"@@events@@" 	        => "",
	"@@di@@"		=> "",
	"@@copyright@@"		=> "put your copyright here",
	"@@commanditems@@"	=> "",
];
```
Some skel files needs more complex processing, for instance a loop to generate html/xml elements with some tokens to replace, for those 
cases you can configure **processors** in the $processors section of the config file of your choice.

A good example can be the di.xml file to configure some events for an Observer....

**Putting all together**

Let's suppose we want to generate a **modulepart.observer** config so that we can add an Observer to an already existing module.
We also want the possibility to generate an etc/frontend/events.xml file so that we can subscribe our Observer to an array of events of our choice. 
We also want this new configuration to show up permanently both in module and modulepart available configs menu.

**Model/res/configs/modulepart.observer.php**
```
<?php
require_once 'module.default.all.php';

//unset all folders that are not part of the Observer
foreach($folders as $key=>$value){
	if(!strstr($key, '/Observer') && $key != '/etc/frontend'):
		unset($folders[$key]);
	endif;
}

//unset all files that are not part of the Observer
foreach($files as $key=>$value):
	if($key != "/Observer/Observer.php" && $key !="/etc/frontend/events.xml"):
		unset($files[$key]);
	endif;
endforeach;
```

Then we have to create/alter/overwrite an array of $processors in the **modulepart.observer** config file.
```
$processors = [
  ['__events__'] = [
  					['subscriptions'] = array(),
  					['processor_file'] = ''
  				];

];
```
then update the $local_replacers array by adding/editing the @@events@@ key 

```
$local_replacers = [
       "@@events@@ => '__events__'
       ...other values
];
```
Then back in the **modulepart.observer** config file you have to define what events you want the Observer to watch.
Let's suppose we want to listen to **the page_block_html_topmenu_gethtml_before** and **page_block_html_topmenu_gethtml_after** events that are triggered by TopMenu component.
With add an array with those events as the value of the subscripitions array of the events token. 
We also specify that we need a module_events.php processor (a php file in the Model/res/processors) to help us produce content 
for the the placeholder @@events@@
```
$processors = [
  ['__events__'] = [
  			['subscriptions'] = [
						'page_block_html_topmenu_gethtml_before',
						'page_block_html_topmenu_gethtml_after'
					     ],
  			['processor_file'] = 'module_events.php'//<-- a file in the Model/res/processors folder
  		];

];
```
Now whenever we parse a file with one or more placeholders matching a key in the processors array the content of his replacers are 
created with the aid of the [Processor Class](https://github.com/mvit777/ranx-generator/blob/master/Generator/Model/Processor.php) and a template file we specify in the **processor_file** value as an additional template.

We now have to add the missing skel that might look like that
**skel/default_etc_frontend_events.skel**
```
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Event/etc/events.xsd">
<!-- see list of available page events https://www.mageplaza.com/magento-2-module-development/magento-2-events.html -->

 @@events@@
</config>
```
**skel/default_observer.skel**
```
<?php

namespace @@vendor@@\@@module@@\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Event\ObserverInterface;

class Observer implements ObserverInterface{
	/**
	* @param EventObserver $observer
	* @return $this
	*/
	public function execute(EventObserver $observer)
	{
		//list of available events -- https://www.mageplaza.com/magento-2-module-development/magento-2-events.html
		$event = $observer->getEventName();
		
		if(method_exists($this, $event)):
			$result = call_user_func_array(array($this, $event), array($observer));
		else:
			error_log("the $event method is not implemented in @@vendor@@/@@module@@/Observer/Observer.php");
		endif;
		
		return $this;
	}
	
	/* Example
	
	protected function page_block_html_topmenu_gethtml_after($observer){
		
		//ex. 
		$menu = $observer->getMenu();
		//add some items
		return "some message";
	}
	
	*/
}
```

```

```

(not fully implemented, to be continued)

## Outputting and Packaging your theme/module ##
As said before, after module or theme generation a new module or theme will reside in the app/code or app/design of your current magento installation (which will be most probably a developer machine).
If it is module it needs to be enabled and all the other usual module installation steps (di:compile etc etc).
If it is a theme it needs to be applied to your store globally or only to some pages of your magento installation.
If you rather want to package and publish your module/theme to a remote repository you can use the 
**ranx:generate:package vendor/theme** command as documented above in the **Usage** section.

## Publishing your theme/module to a remote repository ##
(fully implemented but needs some polishing, also needs better docs on how to configure multiple repositories)

Before using the publishing option you have to configure one or more remote repositories.
If you only want to configure one remote repository (let's say a public Github repository), configuration should be 
pretty straight foward and can be easily figured out by watching at the Generator/Model/res/configs/publisher/config.php 
```
$publishing_options = array(
	'repos' => array(
	    //github branch
	    //repositories must exist and be registered as remotes in local git
		'github'=>'https://github.com/mvit777/m2_ext.git'
	),
	'commands' => array(
		'github'=>'git push -u origin master'
	)
);
```
Basically you put the Generator/bin/code(or design) folder under git.
You add a branch **github** and remote url. 
Then you fill publisher/config.php

Multiple repository configuration is a bit more tricky (better docs needed)
Ex. private on Bitbucket and public on Github (to be continued)

## Todo ##
- add more templates files
- add more configuration options
