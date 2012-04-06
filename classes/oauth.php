<?php

namespace Api;

abstract class Api_OAuth extends Api
{
	public function post($path, $params = array())
	{
		return $this->request($path, $params, 'POST');
	}

	public function request($path, $params = array(), $type = 'GET')
	{
		$params = array_merge(array(
			'oauth_consumer_key' => $this->consumer->key,
			'oauth_token' => $this->token->access_token,
		), $params);

		$url = sprintf($this->api_url, $path);

		$request = \OAuth\Request::forge('resource', $type, $url, $params);
		$request->sign($this->signature, $this->consumer, $this->token);

		return $this->callback($request->execute());
	}

	abstract public function callback($data);
}