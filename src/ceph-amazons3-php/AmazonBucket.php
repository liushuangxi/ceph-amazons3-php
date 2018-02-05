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
     * @param array $args
     * ACL
     * Bucket
     *
     * @return bool|null
     */
    public function createBucket($args = [])
    {
        try {
            if (!isset($args['Bucket'])) {
                return false;
            }

            $this->client->createBucket($args)->toArray();

            return $this->existBucket(['Bucket' => $args['Bucket']]);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deletebucket
     *
     * @param array $args
     * Bucket
     *
     * @return bool|null
     */
    public function deleteBucket($args = [])
    {
        try {
            if (!isset($args['Bucket'])) {
                return true;
            }

            $this->client->deleteBucket($args)->toArray();

            $exist = $this->existBucket(['Bucket' => $args['Bucket']]);
            if (is_null($exist)) {
                return $exist;
            } else {
                return !$exist;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#headbucket
     *
     * @param array $args
     * Bucket
     *
     * @return bool|null
     */
    public function existBucket($args = [])
    {
        try {
            if (!isset($args['Bucket'])) {
                return false;
            }

            $result = $this->client->headBucket($args)->toArray();

            if (isset($result['@metadata']['statusCode']) && $result['@metadata']['statusCode'] == '200') {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listbuckets
     *
     * @param array $args
     *
     * @return array|null
     */
    public function listBuckets($args = [])
    {
        try {
            $buckets = $this->client->listBuckets($args)->toArray();
            $buckets = array_column($buckets['Buckets'], 'Name');

            return $buckets;
        } catch (\Exception $e) {
            return null;
        }
    }
}