<?php

namespace CoreBundle\Service\Storage;

use CoreBundle\Entity\Share;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ShareStorageInterface
{
    public function openStream(Share $share);
    public function uploadShare(Share $share, UploadedFile $uploadedFile);
}