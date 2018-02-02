<?php

namespace Liushuangxi\Ceph;

use Aws\S3\S3Client;

/**
 * Class AmazonBucket
 * @package Liushuangxi\Ceph
 */
class AmazonBucket
{
    /**
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html
     *
     * @var S3Client
     */
    public $client = null;

    /**
     * AmazonBucket constructor.
     *
     * @param $client S3Client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#createbucket
     *
     * @param string $bucket
     * @param string $acl
     * @param array $args
     *
     * @return bool
     */
    public function createBucket($bucket = '', $acl = '', $args = [])
    {
        if (!empty($acl)) {
            $args['ACL'] = $acl;
        }

        if (!empty($bucket)) {
            $args['Bucket'] = $bucket;
        }

        $result = $this->client->createBucket($args)->toArray();

        if (isset($result['@metadata']['statusCode']) && $result['@metadata']['statusCode'] == '200') {
            return true;
        } else {
            return false;
        }
    }

//https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deletebucket

    public function deleteBucket($bucket = '')
    {
        $args = [
            'Bucket' => $bucket
        ];

        $result = $this->client->deleteBucket($args)->toArray();
    }

    /**
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listbuckets
     *
     * @param array $args
     *
     * @return array
     */
    public function listBuckets($args = [])
    {
        $buckets = $this->client->listBuckets($args)->toArray();
        $buckets = array_column($buckets['Buckets'], 'Name');

        return $buckets;
    }
}