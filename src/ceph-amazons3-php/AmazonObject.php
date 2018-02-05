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
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject
     *
     * @param array $args
     * ACL
     * Body
     * Bucket
     * Key
     *
     * @return string|null
     */
    public function createObject($args = [])
    {
        try {
            if (!isset($args['Body'])) {
                return false;
            }

            if (!isset($args['Bucket'])) {
                return false;
            }

            if (!isset($args['Key'])) {
                return false;
            }

            $this->client->putObject($args)->toArray();

            return $this->existObject([
                'Bucket' => $args['Bucket'],
                'Key' => $args['Key']
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deleteobject
     *
     * @param array $args
     * Bucket
     * Key
     *
     * @return bool|null
     */
    public function deleteObject($args = [])
    {
        try {
            if (!isset($args['Bucket'])) {
                return false;
            }

            if (!isset($args['Key'])) {
                return false;
            }

            $this->client->deleteObject($args)->toArray();

            $exist = $this->existObject([
                'Bucket' => $args['Bucket'],
                'Key' => $args['Key']
            ]);

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
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#headobject
     *
     * @param array $args
     * Bucket
     * Key
     *
     * @return bool|null
     */
    public function existObject($args = [])
    {
        try {
            if (!isset($args['Bucket'])) {
                return false;
            }

            if (!isset($args['Key'])) {
                return false;
            }

            $result = $this->client->headObject($args)->toArray();

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
     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listobjects
     *
     * @param array $args
     * Bucket
     *
     * @return array|null
     */
    public function listObjects($args = [])
    {
        try {
            if (!isset($args['Bucket'])) {
                return [];
            }

            $objects = $this->client->listObjects($args)->toArray();
            $objects = array_column($objects['Contents'], 'Key');

            return $objects;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $bucket
     * @param string $key
     * @param int $expire 失效时间，单位S
     *
     * @return string|null
     */
    public function getPrivateObject($bucket, $key, $expire = 60)
    {
        try {
            $command = $this->client->getCommand(
                'GetObject',
                [
                    'Bucket' => (string)$bucket,
                    'Key' => (string)$key
                ]
            );

            $expire = intval($expire);

            $request = $this->client->createPresignedRequest($command, "+$expire seconds");

            return (string)$request->getUri();
        } catch (\Exception $e) {
            return null;
        }
    }
}
