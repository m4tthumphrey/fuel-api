<?php

namespace Api;

class Api_Tumblr extends Api_OAuth
{
	public function api_url()
	{
		return 'http://api.tumblr.com/v2/%s';
	}

	public function request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge($params, array(
			'api_key' => $this->consumer->key
		));

		return parent::request($path, $params, $type);
	}

	public function callback($request)
	{
		try
		{
			$data = $request->execute();
			$data = json_decode($data);

			if ($data->meta->status !== 200)
			{
				throw new ApiException($data->meta->msg);
			}
		}
		catch (\Exception $e)
		{
			// TODO: Parse $e->getMessage() correctly
			throw new ApiException('An error occurred processing this request');
		}

		return $data->response;
	}
}