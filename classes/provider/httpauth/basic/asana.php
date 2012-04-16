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
		return 'https://app.asana.com/-/api/0.1/%s';
	}

	public function callback($request)
	{
		try
		{
			$data = $request->execute();
			$data = json_decode(json_encode($data->response()))->body;
		}
		catch (\RequestStatusException $e)
		{
			$message = 'An error occurred connecting to the Asana servers';
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

		return $data;
	}
}