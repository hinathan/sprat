<?php
namespace Api;
class Container extends Base
{
    public function get()
    {
        $result = array('object'=>'monkey', 'test' => 'always', 'id' => $this->id);
        self::successResponse($result);
    }
    public function put()
    {
        self::emptyRepsonse(); //?
    }
    public function putContent()
    {
        //put $this->child into $this->parent object
        self::emptyRepsonse(); //?
    }
    public function newContainer()
    {
        // presumably _POST contains our data?
        $id = rand();
        $result = array(
            'id' => $id,
            'url' => \Sprat\Util::Url("/container/$id"),
            );
        self::createdResponse($result);
    }
}
