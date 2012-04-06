<?php

namespace Api;

class Api_Twitter extends Api_OAuth
{
	protected $api_url = 'http://api.twitter.com/1/%s.json';

	public static function twitterfy($ret)
	{
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
		$ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);

		return $ret;
	}

	public function callback($data)
	{
		$data = json_decode($data);

		if (isset($data->error)) {
			throw new \ApiException($data->error);
		}

		return $data;
	}
}