<?php

namespace Api;

abstract class Api_HTTP_Auth extends Api
{
	protected $username = null;
	protected $password = null;
	protected $type = null;

	public function __construct($options = array())
	{
		$this->username = $options['username'];
		$this->password = $options['password'];
	}

	public function build_request($path, $params = array(), $type = 'GET')
	{
		$request = parent::build_request($path, $params, $type);

		return $request->http_login($this->username, $this->password, $this->type);
	}
}