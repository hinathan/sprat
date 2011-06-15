<?php
namespace Api;
class Containers extends Base
{
    public function get()
    {
        $result = array();
        for ($i = 0; $i < 100; $i++) {
            $result[] = array('id'=>$i, 'object'=>'monkey', 'test' => 'always');
        }
        self::successResponse($result);
    }
}
