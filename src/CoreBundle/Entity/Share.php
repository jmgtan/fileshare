<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Share
 *
 * @ORM\Table(name="shares")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ShareRepository")
 */
class Share
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\User", fetch="LAZY")
     * @ORM\JoinColumn(name="user_id", nullable=false, )
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="original_filename", type="string", length=255)
     */
    private $originalFilename;

    /**
     * @var string
     *
     * @ORM\Column(name="share_key", type="string", length=255, unique=true)
     */
    private $shareKey;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set originalFilename
     *
     * @param string $originalFilename
     *
     * @return Share
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    /**
     * Get originalFilename
     *
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * Set shareKey
     *
     * @param string $shareKey
     *
     * @return Share
     */
    public function setShareKey($shareKey)
    {
        $this->shareKey = $shareKey;

        return $this;
    }

    /**
     * Get shareKey
     *
     * @return string
     */
    public function getShareKey()
    {
        return $this->shareKey;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Share
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Share
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
}

