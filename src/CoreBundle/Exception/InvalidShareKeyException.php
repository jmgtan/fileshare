<?php
namespace CoreBundle\Exception;


class InvalidShareKeyException extends \Exception
{

    /**
     * InvalidShareKeyException constructor.
     */
    public function __construct()
    {
        $this->message = "Share key not found.";
    }
}