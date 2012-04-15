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
		try
		{
			$data = $request->execute();
			$data = json_decode($data);

			if (isset($data->error))
			{
				throw new ApiException($data->error);
			}
		}
		catch (\Exception $e)
		{
			// TODO: Parse $e->getMessage() correctly
			throw new ApiException('An error occurred processing this request');
		}

		return $data;
	}
}