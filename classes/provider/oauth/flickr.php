<?php

namespace Api;

class Api_Flickr extends Api_OAuth
{
	public function api_url()
	{
		return 'http://api.flickr.com/services/rest';
	}

	public function build_request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge($params, array(
			'method' => 'flickr.' . $path,
			'format' => 'json',
			'nojsoncallback' => 1
		));

		return parent::build_request(null, $params, $type);
	}

	public function callback($request)
	{
		$message = 'An error occurred connecting to the Flickr servers';

		try
		{
			$data = $request->execute();
			$data = json_decode(json_encode($data))->body;

			if ($data->stat === 'fail')
			{
				throw new ApiException($data->message, $data->code);
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