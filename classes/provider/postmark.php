<?php

namespace Api;

class Api_Postmark extends Api
{
	protected $api_key = null;

	public function __construct($options = array())
	{
		$this->api_key = $options['api_key'];
	}

	public function api_url()
	{
		return 'http://api.postmarkapp.com/email';
	}

	public function build_request($path, $params = array(), $type = 'POST')
	{
		$params = \Format::forge($params)->to_json();
		$request = \Request::forge($this->api_url(), array('driver' => 'curl'), $type)

			->set_header('X-Postmark-Server-Token', $this->api_key)
			->set_header('Accept', 'application/json')
			->set_header('Content-Type', 'application/json')
			->set_options(array(
				'SSL_VERIFYPEER' => false,
				'SSL_VERIFYHOST' => false,
				'CUSTOMREQUEST' => $type,
				'POSTFIELDS' => $params
			));

		return $request;
	}

	public function callback($request)
	{
		$message = 'An error occurred connecting to the Postmark servers';
		$code = 0;

		try
		{
			$data = $request->execute();
			$data = json_decode(json_encode($data->response()->body));

			if (isset($data->ErrorCode))
			{
				throw new ApiException($data->Message, $data->ErrorCode);
			}

			return $data;
		}
		catch (\RequestStatusException $e)
		{
			$data = json_decode($e->getMessage());

			if (isset($data->ErrorCode))
			{
				$code = $data->ErrorCode;
				$message = $data->Message;
			}
		}
		catch (ApiException $e)
		{
			throw $e;
		}
		catch (\Exception $e)
		{
			throw new ApiException($e->getMessage());
		}

		throw new ApiException($message, $code);
	}
}