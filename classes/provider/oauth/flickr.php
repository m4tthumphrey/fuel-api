<?php

namespace Api;

class Api_Flickr extends Api_OAuth
{
	public function api_url()
	{
		return 'http://api.flickr.com/services/rest';
	}

	public function request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge($params, array(
			'method' => 'flickr.' . $path,
			'format' => 'json',
			'nojsoncallback' => 1
		));

		return parent::request(null, $params, $type);
	}

	public function callback($request)
	{
		try
		{
			$data = $request->execute();
			$data = json_decode(json_encode($data))->body;

			if ($data->stat === 'fail')
			{
				throw new ApiException($data->message, $data->code);
			}
		}
		catch (ApiException $e)
		{
			throw $e;
		}
		catch (\Exception $e)
		{
			throw new ApiException($e->getMessage());
		}

		return $data;
	}
}