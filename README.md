fuel-api
========

Usage
-----

This cell is designed to be used once you have already authorised. It uses the [OAuth Cell Package](https://github.com/fuel-packages/fuel-oauth). I plan on evolving it for any API I need to use in my current project. It will also support [OAuth2](https://github.com/fuel-packages/fuel-oauth2).

	$options = Config::get('twitter');
	$twitter = Api::forge('twitter', $options);

	$tweet = $twitter->post('statuses/update', array(
		'status' => 'This is a test from fuel-api'
	));

	$tweets = $twitter->get('statuses/user_timeline', array(
		'screen_name' => 'm4tthumphrey',
		'count' => 2,
		'include_rts' => true
	));