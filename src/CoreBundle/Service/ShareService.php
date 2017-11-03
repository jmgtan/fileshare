<?php
namespace CoreBundle\Service;


use CoreBundle\Entity\Share;
use CoreBundle\Entity\User;
use CoreBundle\Exception\InvalidShareKeyException;
use CoreBundle\Service\Storage\ShareStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @param $shareKey
     * @param $password
     * @return Share
     * @throws InvalidShareKeyException
     */
    public function downloadShare($shareKey, $password)
    {
        /** @var Share $share */
        $share = $this->em->getRepository(Share::class)->findOneByShareKey($shareKey);

        if ($share == null) {
            throw new InvalidShareKeyException();
        }

        $storedPassword = $share->getPassword();

        if ($storedPassword != null) {
            if ($storedPassword != $this->encodeSharePassword($password)) {
                throw new InvalidShareKeyException();
            }
        }

        $resource = $this->storage->openStream($share);
        $share->setStorageHandler($resource);

        return $share;
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
     * @param UploadedFile $file
     * @param $password
     * @return Share
     */
    public function createNewShare(User $user, UploadedFile $file, $password)
    {
        $uuid = Uuid::uuid4();

        $share = new Share();
        $share->setUser($user);
        $share->setShareKey($uuid);
        $share->setDateCreated(new \DateTime());
        $share->setOriginalFilename($file->getClientOriginalName());
        $share->setPassword($this->encodeSharePassword($password));
        $share->setFileSize($file->getClientSize());
        $this->em->persist($share);

        $this->storage->uploadShare($share, $file);

        $this->em->flush();

        return $share;
    }

    /**
     * @param $password
     * @return string
     */
    private function encodeSharePassword($password) {
        if ($password != null) {
            $password = hash("sha512", $password);
        }

        return $password;
    }
}