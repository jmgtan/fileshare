<?php
namespace FrontendBundle\Form\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class DownloadShareFormEntity
{
    /**
     * @var string
     * @Assert\NotBlank(message="Share key is a required field.")
     */
    private $shareKey;

    /**
     * @var string
     */
    private $password;

    /**
     * @return string
     */
    public function getShareKey()
    {
        return $this->shareKey;
    }

    /**
     * @param string $shareKey
     */
    public function setShareKey($shareKey)
    {
        $this->shareKey = $shareKey;
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