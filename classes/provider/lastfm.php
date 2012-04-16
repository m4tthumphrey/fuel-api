<?php

namespace Api;

class Api_Lastfm extends Api
{
	protected $api_key = null;
	protected $api_secret = null;
	protected $session_key = null;

	public function __construct($options = array())
	{
		$this->api_key = $options['api_key'];
		$this->api_secret = $options['api_secret'];
		$this->session_key = $options['session_key'];
	}

	public function api_url()
	{
		return 'http://ws.audioscrobbler.com/2.0/';
	}

	public function build_request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge($params, array(
			'api_key' => $this->api_key,
			'method' => $path,
			'sk' => $this->session_key
		));

		$params['api_sig'] = $this->sign($params);
		$params['format'] = 'json';

		return parent::build_request(null, $params, $type);
	}

	protected function sign($params = array())
	{
		ksort($params);

		$string = '';
		foreach ($params as $k => $v)
		{
			$string .= $k . $v;
		}

		return md5($string . $this->api_secret);
	}

	public function callback($request)
	{
		$message = 'An error occurred connecting to the Last.fm servers';

		try
		{
			$data = $request->execute();
			$data = json_decode($data);

			if (isset($data->error))
			{
				throw new ApiException($data->message, $data->error);
			}

			return $data;
		}
		catch (ApiException $e)
		{
			throw $e;
		}
		catch (\Exception $e)
		{
			throw new ApiException($e->getMessage());
		}

		throw new ApiException($message);
	}
}