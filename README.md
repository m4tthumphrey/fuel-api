fuel-api
========

This cell is designed to be used once you have already authorised and have your token. It uses the [OAuth Cell Package](https://github.com/fuel-packages/fuel-oauth) for making requests for OAuth powered APIs. You can also use it for OAuth2 based APIs. Integrated pagination is planned.

Usage
-----

Remember to load copy the config files into your app/config directory. I would recommend setting them to auto load.

Twitter (OAuth)
---------------

	<?php

	function get_tweets()
	{
		$options = Config::get('twitter', true);
		$twitter = Api::forge('twitter', $options);

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
		$options = Config::get('twitter', true);
		$twitter = Api::forge('twitter', $options);

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
		$options = Config::get('foursquare', true);
		$fs = Api::forge('foursquare', $options);

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
-------------------

	<?php

	function get_feed()
	{
		$options = Config::get('instagram', true);
		$instagram = Api::forge('instagram', $options);

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
