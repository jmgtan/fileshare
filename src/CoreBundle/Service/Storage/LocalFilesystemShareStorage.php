<?php
namespace CoreBundle\Service\Storage;

use CoreBundle\Entity\Share;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalFilesystemShareStorage implements ShareStorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $storageLocation;

    /**
     * LocalFilesystemShareStorage constructor.
     * @param EntityManagerInterface $em
     * @param $storageLocation
     */
    public function __construct(EntityManagerInterface $em, $storageLocation)
    {
        $this->em = $em;
        $this->storageLocation = $storageLocation;
    }

    public function openStream(Share $share)
    {
        // TODO: Implement openStream() method.
    }

    public function uploadShare(Share $share, UploadedFile $uploadedFile)
    {
        $uploadedFile->move($this->storageLocation, $share->getShareKey());
    }
}