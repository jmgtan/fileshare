<?php

namespace FrontendBundle\Form\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormEntity
{
    /**
     * @Assert\NotBlank(message="Email is a required field.")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Password is a required field.")
     */
    private $password;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}