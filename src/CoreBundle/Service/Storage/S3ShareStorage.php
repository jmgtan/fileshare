<?php

namespace CoreBundle\Service\Storage;


use CoreBundle\Entity\Share;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3ShareStorage implements ShareStorageInterface
{
    public function openStream(Share $share)
    {
        // TODO: Implement openStream() method.
    }

    public function uploadShare(Share $share, UploadedFile $uploadedFile)
    {
        // TODO: Implement uploadShare() method.
    }

}