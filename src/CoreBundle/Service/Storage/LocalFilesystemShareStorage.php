<?php
namespace CoreBundle\Service\Storage;

use CoreBundle\Entity\Share;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalFilesystemShareStorage implements ShareStorageInterface
{
    /**
     * @var string
     */
    private $storageLocation;

    /**
     * LocalFilesystemShareStorage constructor.
     * @param $storageLocation
     */
    public function __construct($storageLocation)
    {
        $this->storageLocation = $storageLocation;
    }

    public function openStream(Share $share)
    {
        return fopen($this->storageLocation.'/'.$share->getShareKey(), "r");
    }

    public function uploadShare(Share $share, UploadedFile $uploadedFile)
    {
        $uploadedFile->move($this->storageLocation, $share->getShareKey());
    }
}