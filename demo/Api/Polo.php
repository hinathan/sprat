<?php
namespace Api;
class Polo extends Base
{
    public function get()
    {
        $vars = array('a' => $this->a, 'b' => $this->b, 'c' => $this->c);
        return array('echoing' => $vars);
    }
}
