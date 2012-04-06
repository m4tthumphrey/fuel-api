<?php

Autoloader::add_core_namespace('Api');

Autoloader::add_classes(array(
	'Api\\Api'           => __DIR__.'/classes/api.php',
	'Api\\Api_OAuth'           => __DIR__.'/classes/oauth.php',
	'Api\\Api_Twitter'           => __DIR__.'/classes/oauth/twitter.php',

));