<?php

namespace Api;

class Api_Github extends Api_OAuth2
{
	public function api_url()
	{
		return 'https://api.github.com/%s';
	}

	public function token_key()
	{
		return 'access_token';
	}

	public function callback($request)
	{
		try
		{
			$data = $request->execute();
			$data = json_decode($data);
		}
		catch (\RequestStatusException $e)
		{
			$message = 'An error occurred connecting to the Github servers';
			$data = json_decode($e->getMessage());

			if (isset($data->message))
			{
				$message = $data->message;
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