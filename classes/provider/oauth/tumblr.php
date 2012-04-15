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
			$data = json_decode(json_encode($data))->body;

			if ($data->meta->status !== 200)
			{
				throw new ApiException($data->meta->msg);
			}
		}
		catch (ApiException $e)
		{
			throw $e;
		}
		catch (\RequestStatusException $e)
		{
			$message = 'An error occurred connecting to the Tumblr servers';
			$code = 0;
			
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

		return $data->response;
	}
}