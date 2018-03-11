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
someuser@somepc: magento/bin ranx:generate:module <vendorname>/<modulename>
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
if it a theme you will be asked if it is a frontend or admin theme
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
The other config files simply include **module.default.all.php** (or **module.simple.php**) and pop elements from those two arrays when needed.

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
(missing docs)

## Replacers and Processors ##
(missing docs)

## Outputting and Packaging your theme/module ##
(missing docs)

## Publishing your theme/module to a remote repository ##
(missing docs)

## Todo ##
- add more templates files
- add more configuration options
