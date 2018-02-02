<?php

namespace Liushuangxi\Ceph;

use Aws\S3\S3Client;

/**
 * Class AmazonClient
 * @package Liushuangxi\Ceph
 */
class AmazonClient
{
    /**
     * @var array
     */
    public $config = [];

    /**
     * @var S3Client
     */
    public $client = null;

    /**
     * AmazonClient constructor.
     *
     * @param $config
     *
     * host         http://127.0.0.1:1234 带http的完整host路径
     * access_key
     * secret_key
     * url_type     sub_domain/path 子域名/路径
     *
     * 说明：
     *
     * region
     * '' 和 'us-east-1' 作用一样，屏蔽host中的region信息
     * https://docs.aws.amazon.com/zh_cn/AmazonS3/latest/dev/UsingBucket.html#access-bucket-intro
     *
     * @throws \Exception
     */
    public function __construct($config)
    {
        foreach (['host', 'access_key', 'secret_key'] as $key) {
            if (!isset($config[$key])) {
                throw new \Exception("Ceph Config $key Not Exist");
            }
        }

        $this->config = $config;

        $args = [
            'region' => '',
            'version' => '2006-03-01',
            'endpoint' => $this->config['host'],
            'credentials' => [
                'key' => $this->config['access_key'],
                'secret' => $this->config['secret_key'],
            ],
        ];

        if (isset($this->config['url_type'])) {
            switch ($this->config['url_type']) {
                case 'sub_domain':
                    $args['use_accelerate_endpoint'] = true;
                    break;
                case 'path':
                    $args['use_path_style_endpoint'] = true;
                    break;
            }
        }

        try {
            $this->client = new S3Client($args);
        } catch (\Exception $e) {
            throw new \Exception("Ceph Client Create Error: " . $e->getMessage());
        }
    }

    /**
     * @return AmazonBucket
     */
    public function bucket()
    {
        return new AmazonBucket($this->client);
    }

    /**
     * @return AmazonObject
     */
    public function object()
    {
        return new AmazonObject($this->client);
    }
}