<?php

Autoloader::add_core_namespace('Api');

Autoloader::add_classes(array(
	'Api\\Api'								=> __DIR__.'/classes/api.php',
	'Api\\ApiException'				=> __DIR__.'/classes/api.php',
	'Api\\Api_OAuth'					=> __DIR__.'/classes/provider/oauth.php',
	'Api\\Api_Twitter'				=> __DIR__.'/classes/provider/oauth/twitter.php',
	'Api\\Api_Tumblr'					=> __DIR__.'/classes/provider/oauth/tumblr.php',
	'Api\\Api_Dropbox'				=> __DIR__.'/classes/provider/oauth/dropbox.php',
	'Api\\Api_OAuth2'					=> __DIR__.'/classes/provider/oauth2.php',
	'Api\\Api_Foursquare'			=> __DIR__.'/classes/provider/oauth2/foursquare.php',
	'Api\\Api_Instagram'			=> __DIR__.'/classes/provider/oauth2/instagram.php',
	'Api\\Api_Facebook'				=> __DIR__.'/classes/provider/oauth2/facebook.php',
	'Api\\Api_Mailchimp'			=> __DIR__.'/classes/provider/mailchimp.php',

));