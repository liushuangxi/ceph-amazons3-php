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

    /**
     * @param $bucket
     * @param $key
     * @param int $expire 失效时间，单位S
     *
     * @return string
     */
    public function getPrivateObject($bucket, $key, $expire = 60)
    {
        try {
            $cmd = $this->client->getCommand(
                'GetObject',
                [
                    'Bucket' => $bucket,
                    'Key' => $key
                ]
            );

            $expire = intval($expire);

            $request = $this->client->createPresignedRequest($cmd, "+$expire seconds");

            return (string)$request->getUri();
        } catch (\Exception $e) {
            return '';
        }
    }
}