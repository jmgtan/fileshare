<?php

namespace CoreBundle\Service\Storage;


use Aws\AwsClient;
use Aws\S3\S3Client;
use CoreBundle\Entity\Share;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3ShareStorage implements ShareStorageInterface
{
    private $_s3Client;
    private $region;
    private $bucket;

    /**
     * @var string
     */
    private $storageLocation;

    /**
     * S3ShareStorage constructor.
     * @param $storageLocation
     */
    public function __construct($storageLocation, $s3Region, $s3Bucket)
    {
        $this->region = $s3Region;
        $this->bucket = $s3Bucket;

        $this->_s3Client = new S3Client([
            "version" => "latest",
            "region" => $this->region
        ]);

        $this->_s3Client->registerStreamWrapper();
        $this->storageLocation = $storageLocation;
    }

    public function openStream(Share $share)
    {
//        $tempFile = $this->storageLocation."/t_".$share->getShareKey();
//
//        $this->_s3Client->getObject([
//            "Bucket" => $this->bucket,
//            "Key" => $share->getShareKey(),
//            "SaveAs" => $tempFile
//        ]);
//
//        return fopen($tempFile, "r");
        return fopen("s3://".$this->bucket."/".$share->getShareKey(), "r");
    }

    public function uploadShare(Share $share, UploadedFile $uploadedFile)
    {
        $uploadedFile->move($this->storageLocation, $share->getShareKey());

        $this->_s3Client->putObject([
            "Bucket" => $this->bucket,
            "Key" => $share->getShareKey(),
            "SourceFile" => $this->storageLocation.'/'.$share->getShareKey()
        ]);

        unlink($this->storageLocation.'/'.$share->getShareKey());
    }

}