fuel-api
========

This cell is designed to be used once you have already authorised and have your token. It uses the [OAuth Cell Package](https://github.com/fuel-packages/fuel-oauth) for making requests for OAuth powered APIs. You can also use it for other APIs, including OAuth2 based. Integrated pagination is planned.

Providers
---------

The following list of providers are currently supported, feel free to fork and add your own too.

* Asana
* Dropbox
* Facebook
* Flickr
* Foursquare
* Github
* Instagram
* Last.fm
* LinkedIn
* MailChimp
* Tumblr
* Twitter
* Vimeo
* [Twtmore](http://dev.twtmore.com/)

Usage
-----

Remember to load copy the config files into your app/config directory. I would recommend setting them to auto load.

Twitter (OAuth)
---------------

	<?php

	function get_tweets()
	{
		$provider = 'twitter';
		if (($options = Config::load($provider)) === false) {
			$options = Config::get($provider);
		}

		$twitter = Api::forge($provider, $options);

		try
		{
			$tweets = $twitter->get('statuses/user_timeline', array(
				'screen_name' => 'm4tthumphrey',
				'count' => 2,
				'include_rts' => true
			));
		}
		catch (Api\ApiException $e)
		{
			$tweets = array();
			logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
		}

		return $tweets;
	}

	public function post_tweet()
	{
		$provider = 'twitter';
		if (($options = Config::load($provider)) === false) {
			$options = Config::get($provider);
		}

		$twitter = Api::forge($provider, $options);

		try
		{
			$tweet = $twitter->post('statuses/update', array(
				'status' => 'This is a test tweet from fuel-api'
			));
		}
		catch (Api\ApiException $e)
		{
			$tweet = null;
			logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
		}

		return $tweet;
	}

Foursquare (OAuth2)
-------------------

	<?php

	function get_trending_venues($ll = '40.7,-74')
	{
		$provider = 'foursquare';
		if (($options = Config::load($provider)) === false) {
			$options = Config::get($provider);
		}

		$fs = Api::forge($provider, $options);

		try
		{
			$venues = $fs->get('venues/trending', array(
				'll' => $ll
			))->venues;

		}
		catch (Api\ApiException $e)
		{
			$venues = array();
			logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
		}

		return $venues;
	}

Instagram (OAuth2)
------------------

	<?php

	function get_feed()
	{
		$provider = 'instagram';
		if (($options = Config::load($provider)) === false) {
			$options = Config::get($provider);
		}

		$instagram = Api::forge($provider, $options);

		try
		{
			$feed = $instagram->get('users/self/feed');
		}
		catch (Api\ApiException $e)
		{
			$feed = array();
			logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
		}

		return $feed;
	}

Facebook (OAuth2)
-----------------

We use the [Graph API](http://developers.facebook.com/docs/reference/api/) when making requests to Facebook. Remember this is for use once authenticated and you have a valid token. Make sure you authenicate with the [necessary permissions](http://developers.facebook.com/docs/authentication/permissions/) to be able to complete the requests.

	<?php

	function update_status()
	{
		$provider = 'facebook';
		if (($options = Config::load($provider)) === false) {
			$options = Config::get($provider);
		}

		$fb = Api::forge($provider, $options);

		try
		{
			$status_id = $fb->post('me/feed', array(
				'message' => 'This is a test update from fuel-api'
			));
		}
		catch (Api\ApiException $e)
		{
			$status_id = null;
			logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
		}

		return $status_id;
	}

Last.fm
-------

	<?php

	function shout()
	{
		$provider = 'lastfm';
		if (($options = Config::load($provider)) === false) {
			$options = Config::get($provider);
		}

		$lfm = Api::forge($provider, $options);

		try
		{
			$lfm->post('user.shout', array(
				'user' => 'm4tthumphrey',
				'message' => 'This is a test shout from fuel-api'
			));
		}
		catch (Api\ApiException $e)
		{
			logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
		}
	}

Asana (HTTP Auth Basic)
-----------------------

	<?php

	function user_info()
	{
		$provider = 'asana';
		if (($options = Config::load($provider)) === false) {
			$options = Config::get($provider);
		}

		$asana = Api::forge($provider, $options);

		try
		{
			$user = $asana->get('users/me');
		}
		catch (Api\ApiException $e)
		{
			$user = null;
			logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
		}

		return $user;
	}

Twtmore
-----------------------

	<?php

	function user_info()
	{
		$provider = 'twtmore';
		if (($options = Config::load($provider)) === false) {
			$options = Config::get($provider);
		}

		$asana = Api::forge($provider, $options);

		try
		{
			$user = $asana->post('shorten', array(
				"user" => "username",
				"tweet" => "This is a long tweet..."
			));
		}
		catch (Api\ApiException $e)
		{
			$user = null;
			logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
		}

		return $user;
	}
