<?php

namespace Api;

class Api_Googlemaps extends Api
{
	public function __construct($options = array())
	{
	}

	public function api_url()
	{
		return 'http://maps.googleapis.com/maps/api/%s/json';
	}

	public function callback($request)
	{
		$message = 'An error occurred connecting to the Google Map servers';

		try
		{
			$data = $request->execute();
			$data = json_decode($data);

			if ($data->status != 'OK')
			{
				throw new ApiException($data->status);
			}

			return $data;
		}
		catch (\Exception $e)
		{
			throw new ApiException($e->getMessage());
		}

		throw new ApiException($message);
	}
}