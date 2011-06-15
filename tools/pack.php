<?php

print "<?php\n\n";

foreach (array('/lib/', '/demo/') as $origin) {
    $path = dirname(__DIR__) . $origin;
    print packFrom($path);
}

function packFrom($libs)
{

    $classes = get_declared_classes();
    $source = '';
    $ite = new RecursiveDirectoryIterator($libs);
    foreach (new RecursiveIteratorIterator($ite) as $filename=>$cursor) {
        if (!preg_match('/\.php$/', $filename)) {
            continue;
        }
        if (preg_match('/packed/',basename($filename))) {
            continue;
        }
        if (!preg_match('/[A-Z]/',basename($filename))) {
            continue;
        }

        $temp = tempnam('/tmp','pack');
        if (true) {
            `php -w $filename > $temp`;
        } else {
            `cp $filename $temp`;
        }
        $lines = file($temp);
        unlink($temp);
        array_shift($lines);
        $source .= "\n";
        //$source .= "/* $filename */\n";
        $source .= implode("", $lines);
    }
    return $source;
}
