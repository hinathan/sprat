<?php
namespace Sprat\Exception;
class InvalidRequest extends \Sprat\Exception
{
    public function __construct($message)
    {
        parent::__construct(400, "Invalid Request", $message);
    }
}