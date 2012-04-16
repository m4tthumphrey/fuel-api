<?php

namespace Api;

class Api_Twtmore extends Api
{
    protected $api_key = null;

    public function __construct($options = array())
    {
        $this->api_key = $options['api_key'];
    }

    public function api_url()
    {
        return 'http://api.twtmore.com/v4/';
    }

    public function build_request($path, $params = array(), $type = 'GET')
    {
        $params = \Arr::merge($params, array(
            'apikey' => $this->api_key,
        ));

        return parent::build_request($path, $params, $type);
    }

    public function callback($request)
    {
        try
        {
            $data = $request->execute();
            $data = json_decode($data);

            return $data;
        }
        catch (\RequestStatusException $e)
        {
            $data = $request->response();
            $data = json_decode($data);

            throw new ApiException($data->error);
        }
        catch (\Exception $e) { }

        throw new ApiException("Failed to perform request");
    }
}