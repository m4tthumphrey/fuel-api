<?php

namespace Api;

class Api_Twtmore extends Api
{
	protected $api_key = null;

	public function __construct($options = array())
	{
		$this->api_key = $options['api_key'];
	}

	public function api_url()
	{
		return 'http://api.twtmore.com/v3/%s';
	}

	public function build_request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge($params, array(
			'apikey' => $this->api_key,
		));

		return parent::build_request($path, $params, $type);
	}

	public function callback($request)
	{
		$message = 'An error occurred connecting to the Twtmore servers';

		try
		{
			$data = $request->execute();
			$data = json_decode($data);

			return $data;
		}
		catch (\RequestStatusException $e)
		{
			$data = json_decode($e->getMessage());

			if (isset($data->error))
			{
				$message = $data->error;
			}

			throw new ApiException($message);
		}
		catch (\Exception $e)
		{
			throw new ApiException($e->getMessage());
		}

		throw new ApiException($message);
	}
}