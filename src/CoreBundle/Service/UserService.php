<?php

namespace CoreBundle\Service;

use CoreBundle\Entity\User;
use CoreBundle\Exception\DuplicateEmailException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function isEmailRegistered($email)
    {
        $repository = $this->em->getRepository(User::class);

        $user = $repository->findOneByEmailAddress($email);

        return $user != null;
    }

    public function register($email, $password)
    {
        if ($this->isEmailRegistered($email)) {
            throw new DuplicateEmailException();
        }

        $user = new User();
        $user->setDateCreated(new \DateTime());
        $user->setEmailAddress($email);
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();
    }
}