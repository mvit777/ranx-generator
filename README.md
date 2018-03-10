# Ranx/Generator
A command line extension for magento-2.2.2 ce to build theme/module boilerplate

## Features ##

- Generates files and folders for Magento 2 Module or Theme boilerplate
- Generation can be configured via plain php arrays
- Multiple configurations are possible, that means one can generate different types of modules 
  ranging from the very basic module/theme with just the bare minimum to a complete module/theme
- Partial generation is possible, so that elements can be added after first generation
- It can also optionally create a magento package out of the source files
- Validate it against Magento official validator (bundled)
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
ranx
  ranx:generate:module                     Module skeleton generation. 
                                           Expects a Vendor/ModuleName string 
                                           as required argument.
  ranx:generate:modulepart                 Module partial skeleton generation. 
                                           Expects a Vendor/ModuleName string to an existing module 
                                           as required argument.
  ranx:generate:package                    Packages an existing module or theme. 
                                           Expects a Vendor/Module/ThemeName string 
                                           to an existing module or theme as required argument.
  ranx:generate:theme                      Skeleton generation for a custom theme. 
                                           Expects a Vendor/ModuleName string to an existing theme
                                           as required argument.
```
## Usage ##
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
Choice 0 (module.simple.php) builds only a very basic module with only required files. 
Should you decide to add another element (Ex. a controller) without recreating the whole module 
you can use the modulepart command

```
 bin/magento ranx:generate:modulepart Test/Ranx_catalog
```
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
## Adding your config files ##
(missing docs)

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
