<?php

namespace Api;

class Api_Linkedin extends Api_OAuth
{
	public function api_url()
	{
		return 'http://api.linkedin.com/v1/%s';
	}

	public function request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge($params, array(
			'format' => 'json'
		));

		return parent::request($path, $params, $type);
	}

	public function callback($request)
	{
		try
		{
			$data = $request->execute();
			$data = json_decode($data);
		}
		catch (ApiException $e)
		{
			throw $e;
		}
		catch (\RequestStatusException $e)
		{
			$message = 'An error occurred connecting to the LinkedIn servers';
			$code = 0;
			$data = json_decode($e->getMessage());

			if (isset($data->message))
			{
				$message = $data->message;
				$code = $data->errorCode;
			}

			throw new ApiException($message, $code);
		}
		catch (\Exception $e)
		{
			throw new ApiException($e->getMessage());
		}

		return $data;
	}
}