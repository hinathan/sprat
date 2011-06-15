<?php
namespace Sprat\Exception;
class InvalidParameter extends \Sprat\Exception
{
    public function __construct($message)
    {
        parent::__construct(400, "Invalid Parameter", $message);
    }
}