<?php
namespace Sprat;
class Util
{
    public static function URL($path = null, $extras = array())
    {
        $base = array(
            'scheme' => 'http',
            'host' => $_SERVER['HTTP_HOST'],
            'path' => isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:'',
            'query' => http_build_query($_GET),
        );

        if (is_string($path)) {
            $base['path'] = $path;
        }

        if (is_array($extras)) {
            $argset = $_GET;
            foreach ($extras as $key=>$value) {
                $argset[$key] = $value;
            }
            $base['query'] = http_build_query($argset);
        }
        if (null === $extras) {
            $base['query'] = '';
        }

        return http_build_url($base);
    }
}