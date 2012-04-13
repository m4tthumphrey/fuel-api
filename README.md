fuel-api
========

Usage
-----

This cell is designed to be used once you have already authorised. It uses the [OAuth Cell Package](https://github.com/fuel-packages/fuel-oauth) for making requests for OAuth powered APIs. I plan on evolving it for any API I need to use in my current project. You can also use it for OAuth2 based APIs.

Remember to load copy the config files into your app/config directory. I would recommend setting them to auto load.

Twitter (OAuth)
---------------

	public function get_tweets()
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
			logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
		}

		return $tweets;
	}

Foursquare (OAuth2)
-------------------

	public function get_trending_venues($ll = '40.7,-74')
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