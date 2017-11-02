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
     * LocalFilesystemShareStorage constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function openStream(Share $share)
    {
        // TODO: Implement openStream() method.
    }

    public function uploadShare(Share $share, UploadedFile $uploadedFile)
    {
        // TODO: Implement uploadShare() method.
    }
}