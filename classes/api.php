<?php

namespace Api;

class ApiException extends \Exception {}

abstract class Api
{
	public static function forge($api, $options = array())
	{
		$class = \Inflector::words_to_upper('Api_' . $api);

		return new $class($options);
	}

	protected $consumer = null;
	protected $token = null;

	protected $api_url = null;

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

		$this->signature = \OAuth\Signature::forge('HMAC-SHA1');

		return $this;
	}

	public function get($path, $params = array(), $cache = null)
	{
		$hash = static::cache_hash($path, $params);

		if ($cache === null) {
			$cache_value = false;
		} elseif ($cache === true) {
			$cache_value = null;
		} else {
			$cache_value = $cache;
		}

		try {
			if ($cache === false) {
				throw new \CacheNotFoundException;
			}

			$data = \Cache::get($hash);
		} catch (\CacheNotFoundException $e) {
			try {
				$data = $this->request($path, $params, 'GET');

				\Cache::set($hash, $data, $cache_value);
			} catch (\Exception $e) {
				$data = array();

				logger(\Fuel::L_ERROR, $e->getMessage(), __METHOD__);
			}
		}

		return $data;
	}

	public static function cache_hash($path, $params = array())
	{
		return md5($path . '|' . implode('|', array_map(create_function(
			'$k,$v', 'return $k . "=" . $v;'
		), array_keys($params), $params)));
	}
}