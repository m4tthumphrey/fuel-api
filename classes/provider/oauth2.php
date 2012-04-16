<?php

namespace Api;

abstract class Api_OAuth2 extends Api
{
	protected $token = null;

	abstract public function token_key();

	public function __construct($options = array())
	{
		$this->token = $options['access_token'];
	}

	public function build_request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge($params, array(
			$this->token_key() => $this->token
		));

		return parent::build_request($path, $params, $type);
	}
}