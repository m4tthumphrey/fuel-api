<?php

namespace Api;

class Api_Mailchimp extends Api
{
	protected $dc = null;
	protected $key = null;
	protected $secure = null;

	public function __construct($options = array())
	{
		$this->key = $options['api_key'];
		$this->dc = substr($this->key, strpos($this->key, '-') + 1);
		$this->secure = (bool) $options['secure'];
	}

	public function api_url()
	{
		$protocol = 'http';

		if ($this->secure)
		{
			$protocol .= 's';
		}

		return $protocol .= '://' . $this->dc . '.api.mailchimp.com/1.3/?method=%s';
	}

	public function request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge($params, array(
			'apikey' => $this->key
		));

		return parent::request(urlencode($path), $params, $type);
	}

	public function callback($request)
	{
		try
		{
			$data = $request->execute();
			$data = $data->response()->body;

			if (isset($data['error']))
			{
				throw new ApiException($data['error'], $data['code']);
			}

			throw new ApiException('An error occurred connecting to the MailChimp servers');
		}
		catch (\Exception $e)
		{
			throw new ApiException($e->getMessage());
		}

		return $data;
	}
}