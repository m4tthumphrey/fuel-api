<?php

namespace Api;

class Api_Twitter extends Api_OAuth
{
	public function api_url()
	{
		return 'http://api.twitter.com/1/%s.json';
	}

	public static function twitterfy($ret)
	{
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
		$ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);

		return $ret;
	}

	public function callback($request)
	{
		try
		{
			$data = $request->execute();
			$data = json_decode($data);

			if (isset($data->error))
			{
				throw new ApiException($data->error);
			}
		}
		catch (ApiException $e)
		{
			throw $e;
		}
		catch (\Exception $e)
		{
			// TODO: Parse $e->getMessage() correctly
			throw new ApiException('An error occurred processing this request');
		}

		return $data;
	}
}