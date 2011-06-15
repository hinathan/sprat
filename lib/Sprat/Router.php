<?php
namespace Sprat;
class Router
{
    const DELIM = '/';
    const MARK = ':';
    const MARKNUMERIC = '#';
    const ALTERNATE = '|';
    const METHODS = 0;
    const PATHSPEC = 1;
    const CONTROLLER = 2;
    const OVERRIDE_METHOD = 3;
    private static $allowedMethods = array(
        'GET'=>'GET','PUT'=>'PUT','POST'=>'POST','PATCH'=>'PATCH','DELETE'=>'DELETE',
        );
    private static $_instance = null;
    private $namespace;
    private $routes;
    public function __construct($namespace, $routes)
    {
        if (self::$_instance) {
           throw new Exception(500, "No such");
        }
        self::$_instance = $this;
        $this->namespace = $namespace;
        $this->routes = $routes;
    }
    public function run()
    {

        $method = $_SERVER['REQUEST_METHOD'];
        if (!isset(self::$allowedMethods[$method])) {
           throw new Exception\InvalidRequest("$method request method not allowed");
        }
        $parts = explode(self::DELIM, trim($_SERVER['REDIRECT_URL'], self::DELIM));

        foreach ($this->routes as $route) {
            if(false === strpos($route[self::METHODS], $method)) {
                continue;
            }
            $spec = $route[self::PATHSPEC];
            $specParts = explode(self::DELIM, $spec);
            if(count($specParts) !== count($parts)) {
                continue;
            }
            $vars = array();
            foreach($specParts as $i => $part) {
                if($part{0} === self::MARK) {
                    $vars[substr($part, 1)] = $parts[$i];
                } else if($part{0} === self::MARKNUMERIC) {
                    $partName = substr($part, 1);
                    if(is_numeric($parts[$i])) {
                        $vars[$partName] = intval($parts[$i]);
                    } else {
                        throw new Exception\InvalidParameter("$part must be an integer in $spec");
                    }
                } else if ($part === $parts[$i]) {
                    // matches
                } else {
                    continue(2);
                }
            }
            $handler = $route[self::CONTROLLER];

            $class = $this->namespace . '\\' . $handler;
            $object = new $class();

            foreach($vars as $key => $value) {
                $object->$key = $value;
            }

            if(isset($route[self::OVERRIDE_METHOD])) {
                $method = $route[self::OVERRIDE_METHOD];
            }

            $call = array($object, $method);

            if (!is_callable($call)) {
                throw new Exception\InvalidRequest("Bad Request - route error '$method'");
            }
            call_user_func($call);
            return;
        }

        Response::errorResponse(400, "Bad Request");
    }
}
