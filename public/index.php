<?php

$routes = array(
    array('GET,PATCH,DELETE','container/#id','Container'),
    array('GET','containers','Containers'),
    array('PUT','container/#parent/contents/#child','Container','putContent'),
    array('GET,DELETE','contents/#id','Container'),
    array('GET','echo/:a/#b/#c','Polo'),
    array('GET','polo/:a/#b/#c','Polo'),
    array('PUT,POST','container','Container','newContainer'),
    array('PUT,POST','container/#id/comments','newComment'),
    array('GET','docs','Docs'),
);

$router = new \Sprat\Router('\Api', $routes);
$router->run($routes);
