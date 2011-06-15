<?php

//require dirname(__DIR__) . '/packed.both.php';

spl_autoload_register(function($class) {
    foreach (array('/','/../lib/') as $sub) {
        $path = __DIR__ . $sub . strtr($class,'\\_','//') . '.php';
        if (file_exists($path)) {
            require $path;
            return true;
        }
    }
    return false;
}, true, true);

$prior_exception_handler = null;
$handler = function($exception) use ($prior_exception_handler) {
    if ($exception instanceof \Sprat\Exception) {
        $exception->fire();
    } else {
        call_user_func($prior_exception_handler, $Exception);
    }
};
$prior_exception_handler = set_exception_handler($handler);

set_error_handler(function($errno, $errstr, $errfile, $errline, $context) {
    if (!(error_reporting() & $errno)) {
        return;
    }
    $extra = (file_exists($errfile)?filesize($errfile):'unknown') . ".$errline.$errno";
    if (true) {
        $url = "txmt://open/?url=file://$errfile&line=$errline";
        $extra .= " ($errstr) <a href=\"$url\">$errfile:$errline</a>";
    }
    $exception = new \Sprat\Exception\InternalError($extra);
    $exception->fire();
});

