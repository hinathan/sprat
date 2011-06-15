<?php

namespace Sprat;
class Response
{
    public static $headers = array(
        'Content-Type' => 'application/javascript',
        );
    public static $rels = array();
    public static $didEmit = false;

    public static function addRel($type, $url)
    {
        self::$rels[$type] = $url;
    }
    public static function staticResponse($file)
    {
        if(self::$didEmit) {
            return;
        }

        if(file_exists($file)) {
            header("Content-Type: text/html");
            $handle = fopen($file, 'r');
            fpassthru($handle);
            fclose($handle);
            self::$didEmit = true;
            return;
        }
        throw new Exception\FileNotFound($file);
    }
    public static function createdResponse($result)
    {
        Response::successResponse($result, 201, "Created");
    }
    public static function emptyResponse()
    {
        Response::successResponse(null, 204, "No Content");
    }
    public static function errorResponse($status, $message, $code = 'unknown')
    {
        Response::addRel('reference', Util::URL('/docs', null));
        Response::successResponse(array('error' => $code), $status, $message);
    }

    public static function successResponse($result, $status = 200, $message = "OK")
    {
        if (self::$didEmit) {
            return;
        }

        $canHeader = true;
        if (headers_sent()) {
            $canHeader = false;
        }

        $callback = false;
        if (isset($_GET['callback'])) {
            $callback = $_GET['callback'];
        }
        $verbose = false;
        if (isset($_GET['verbose'])) {
            $verbose = $_GET['verbose'];
        }

        $headers = self::$headers;
        $canHeader && header($_SERVER['SERVER_PROTOCOL'] . " $status $message");
        $headers['status'] = $status;

        self::$headers['Expires'] = "Sun, 19 Nov 1978 05:00:00 GMT";
        self::$headers['Cache-Control'] = "no-store, no-cache, must-revalidate, post-check=0, pre-check=0";
        $links = array();
        foreach (self::$rels as $rel => $url) {
            $links[] = array($url, array('rel' => $rel));
        }

        unset($headers['Content-Type']);
        foreach (self::$headers as $key => $value) {
            $canHeader && header("$key: $value");
        }
        if ($links) {
            $linkset = array();
            foreach ($links as $url_rel) {
                $linkset[] = sprintf('<%s>; rel="%s"', $url_rel[0], $url_rel[1]['rel']);
            }
            $canHeader && header("Link: " . implode(", ", $linkset));
           $headers['Link'] = $links;
        }
        if ($callback) {
            $final = array(
                'meta' => $headers,
                'data' => $result,
                );
            print "$callback(" . json_encode($final) . ")\n";
        } else if($verbose) {
            $final = array(
                'meta' => $headers,
                'data' => $result,
                );
            print json_encode($final) . "\n";
        } else if ($result === null) {
            // no output
        } else {
            print json_encode($result) . "\n";
        }

        self::$didEmit = true;
    }

}