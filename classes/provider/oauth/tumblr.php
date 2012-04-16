<?php

namespace Api;

class Api_Tumblr extends Api_OAuth
{
	public function api_url()
	{
		return 'http://api.tumblr.com/v2/%s';
	}

	public function callback($request)
	{
		$message = 'An error occurred connecting to the Tumblr servers';
		$code = 0;

		try
		{
			$data = $request->execute();
			$data = json_decode(json_encode($data))->body;

			return $data->response;
		}
		catch (\RequestStatusException $e)
		{
			$data = json_decode($e->getMessage());

			if (isset($data->meta->msg))
			{
				$message = $data->meta->msg;
				$code = $data->meta->status;
			}

			throw new ApiException($message, $code);
		}
		catch (\Exception $e)
		{
			throw new ApiException($e->getMessage());
		}

		throw new ApiException($message, $code);
	}
}