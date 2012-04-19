<?php

namespace Api;

class Api_Asana extends Api_HTTP_Auth_Basic
{
	public function __construct($options = array())
	{
		$this->username = $options['api_key'];
	}

	public function api_url()
	{
		return 'https://app.asana.com/api/1.0/%s';
	}

	public function callback($request)
	{
		$message = 'An error occurred connecting to the Asana servers';

		try
		{
			$data = $request->execute();
			$data = json_decode(json_encode($data->response()))->body;

			return $data;
		}
		catch (\RequestStatusException $e)
		{
			$data = json_decode($e->getMessage());

			if (isset($data->errors))
			{
				$message = current($data->errors)->message;
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