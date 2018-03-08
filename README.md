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
  ranx:generate:module                     A command line extension for module skeleton generation. 
                                           Expects a Vendor/ModuleName string as required argument.
                                           Accepts a path to a config file as optional argument
  ranx:generate:modulepart                 A command line extension for modulepart skeleton generation. 
                                           Expects a Vendor/ModuleName string as required argument.
                                           Expects a path to a config file as required argument
  ranx:generate:theme                      A command line extension for theme skeleton generation. 
                                           Expects a Vendor/ModuleName string as required argument.
                                           Ask for a path to a config file as required argument
  ranx:module:package  
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

## Todo ##
- add more templates files
- add more configuration options
