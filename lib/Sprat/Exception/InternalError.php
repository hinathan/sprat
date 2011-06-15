<?php
namespace Sprat\Exception;
class InternalError extends \Sprat\Exception
{
    public function __construct($message)
    {
        parent::__construct(500, "Internal Error", $message);
    }
}