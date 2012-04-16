<?php

namespace Api;

class ApiException extends \FuelException {}

abstract class Api
{
	public static function forge($api, $options = array())
	{
		$class = \Inflector::words_to_upper('Api_' . $api);

		return new $class($options);
	}

	abstract public function __construct($options = array());

	abstract public function api_url();

	public function get($path, $params = array(), $cache = null)
	{
		$hash = static::cache_hash($path, $params);

		if ($cache === null)
		{
			$cache_value = \Config::get('cache_lifetime');
		}
		elseif ($cache === true)
		{
			$cache_value = null;
		}
		else
		{
			$cache_value = $cache;
		}

		try
		{
			if ($cache === false)
			{
				throw new \CacheNotFoundException;
			}

			$data = \Cache::get($hash);
		}
		catch (\CacheNotFoundException $e)
		{
			$data = $this->request($path, $params, 'GET');

			\Cache::set($hash, $data, $cache_value);
		}

		return $data;
	}

	public function post($path, $params = array())
	{
		return $this->request($path, $params, 'POST');
	}

	public function delete($path, $params = array())
	{
		return $this->request($path, $params, 'DELETE');
	}

	public function build_request($path, $params = null, $type = 'GET')
	{
		$url = sprintf($this->api_url(), $path);

		$request = \Request::forge($url, array('driver' => 'curl'), $type)
			->set_options(array(
				'SSL_VERIFYPEER' => false,
				'SSL_VERIFYHOST' => false,
				'CUSTOMREQUEST' => $type
			));

		if ($params) {
			$request->set_params($params);
		}

		return $request;
	}

	public function request($path, $params = null, $type = 'GET')
	{
		$request = $this->build_request($path, $params, $type);
		
		return $this->callback($request);
	}

	abstract public function callback($request);

	public static function cache_hash($path, $params = array())
	{
		return md5($path . '|' . implode('|', array_map(create_function(
			'$k,$v', 'return $k . "=" . $v;'
		), array_keys($params), $params)));
	}
}