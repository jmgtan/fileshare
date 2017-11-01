<?php
namespace CoreBundle\Exception;

class DuplicateEmailException extends \Exception
{

    /**
     * DuplicateEmailException constructor.
     */
    public function __construct()
    {
        $this->message = "Email already registered";
    }
}