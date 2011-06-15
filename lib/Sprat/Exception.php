<?php
namespace Sprat;
class Exception extends \Exception
{
    public $HttpCode = 500;
    public $Error = null;
    public function __construct($HttpCode, $Message, $Error = null)
    {
        $this->HttpCode = $HttpCode;
        $this->Error = $Error;
        parent::__construct($Message);
    }
    public function fire()
    {
        $safer = str_replace("\n", ' - ', $this->getMessage());
        if(!headers_sent()) {
            //header($_SERVER['SERVER_PROTOCOL'] . " $this->HttpCode " . $safer);
            Response::errorResponse($this->HttpCode, $this->getMessage(), $this->Error);
            exit;
        } else {
            print "\n------\nFatal error after initial output: ";
            print $safer;
            exit;
        }
    }
}
