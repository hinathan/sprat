<?php
namespace Api;
class Docs extends Base
{
    public function get()
    {
        self::staticResponse('docs.html');
    }
}
