<?php

namespace Api;

class Api_Dropbox extends Api_OAuth
{
	public function api_url()
	{
		return 'https://api.dropbox.com/1/%s';
	}

	public function callback($request)
	{
		$message = 'An error occurred connecting to the Dropbox servers';

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
				$message = current($data->error);
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