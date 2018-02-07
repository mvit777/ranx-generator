<?php

$publishing_options = array(
	'repos' => array(
	    //github branch
	    //repositories must exist and be registered as remotes in local git
		'github'=>'https://github.com/mvit777/m2_ext.git',
		'mymirror'=>'https://marcello71@bitbucket.org/marcello71/m2_extensions.git'
	),
	'commands' => array(
		'github'=>'git push -u origin master',
		'mymirror'=>'git push -u mymirror master'
	)

);
