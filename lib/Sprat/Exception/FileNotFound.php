<?php
namespace Sprat\Exception;
class FileNotFound extends \Sprat\Exception
{
    public function __construct($message)
    {
        parent::__construct(404, "Not Found", $message);
    }
}