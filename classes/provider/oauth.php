<?php

namespace Api;

abstract class Api_OAuth extends Api
{
	protected $consumer = null;
	protected $token = null;
	protected $signature_type = 'HMAC-SHA1';

	public function __construct($options = array())
	{
		$this->consumer = \OAuth\Consumer::forge(array(
			'key' => $options['consumer_key'],
			'secret' => $options['consumer_secret']
		));

		$this->token = \OAuth\Token::forge('access', array(
			'access_token' => $options['access_token'],
			'secret' => $options['access_secret']
		));

		$this->signature = \OAuth\Signature::forge($this->signature_type);

		return $this;
	}

	public function build_request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge(array(
			'oauth_consumer_key' => $this->consumer->key,
			'oauth_token' => $this->token->access_token,
		), $params);

		$url = sprintf($this->api_url(), $path);

		$request = \OAuth\Request::forge('resource', $type, $url, $params);
		$request->sign($this->signature, $this->consumer, $this->token);

		return $request;
	}
}