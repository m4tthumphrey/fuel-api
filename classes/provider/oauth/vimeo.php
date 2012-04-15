<?php

namespace Api;

class Api_Vimeo extends Api_OAuth
{
	public function api_url()
	{
		return 'http://vimeo.com/api/rest/v2';
	}

	public function request($path, $params = array(), $type = 'GET')
	{
		$params = \Arr::merge($params, array(
			'method' => $path,
			'format' => 'json'
		));

		return parent::request(null, $params, $type);
	}

	public function callback($request)
	{
		try
		{
			$data = $request->execute();
			$data = json_decode(json_encode($data))->body;

			if (isset($data->err))
			{
				throw new ApiException($data->err->expl, $data->err->code);
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

		return $data;
	}
}