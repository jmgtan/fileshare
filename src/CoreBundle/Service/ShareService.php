<?php
namespace CoreBundle\Service;


use CoreBundle\Entity\Share;
use CoreBundle\Entity\User;
use CoreBundle\Service\Storage\ShareStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class ShareService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ShareStorageInterface
     */
    private $storage;

    /**
     * ShareService constructor.
     * @param EntityManagerInterface $em
     * @param ShareStorageInterface $storage
     */
    public function __construct(EntityManagerInterface $em, ShareStorageInterface $storage)
    {
        $this->em = $em;
        $this->storage = $storage;
    }

    /**
     * @param User $user
     * @return Share[]
     */
    public function findLatestShares(User $user)
    {
        $repository = $this->em->getRepository(Share::class);

        return $repository->findBy(['user' => $user], ['dateCreated' => 'desc'], 10, 0);
    }

    /**
     * @param User $user
     * @param $originalFileName
     * @param $password
     * @param $tempFileLocation
     * @return Share
     */
    public function createNewShare(User $user, $originalFileName, $password, $tempFileLocation)
    {
        $uuid = Uuid::uuid4();

        $share = new Share();
        $share->setUser($user);
        $share->setShareKey($uuid);
        $share->setDateCreated(new \DateTime());
        $share->setOriginalFilename($originalFileName);
        $share->setPassword($password);

        $this->em->persist($share);

        $this->storage->uploadShare($share, $tempFileLocation);

        $this->em->flush();

        return $share;
    }
}