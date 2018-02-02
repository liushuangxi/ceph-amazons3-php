<?php

namespace Liushuangxi\Ceph;

use Aws\S3\S3Client;

/**
 * Class AmazonObject
 * @package Liushuangxi\Ceph
 */
class AmazonObject
{
    /**
     * @var S3Client
     */
    public $client = null;

    /**
     * AmazonObject constructor.
     *
     * @param $client S3Client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }
}