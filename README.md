# ceph-amazons3-php

ceph amazons3 client for php

## 安装
<pre>
composer require liushuangxi/ceph-amazons3-php -vvv
</pre>

## 使用
<pre>
$config = [
    'host' => 'http://127.0.0.1:1234',
    'access_key' => 'access_key',
    'secret_key' => 'secret_key',
    'url_type' => 'path'
];
</pre>

<pre>
$client = new \Liushuangxi\Ceph\AmazonClient();
</pre>

### Bucket操作
<pre>
$client->bucket()->listBuckets($args);
$client->bucket()->createBucket($args);
$client->bucket()->deleteBucket($args);
$client->bucket()->existBucket($args);
</pre>

### 对象操作
<pre>
$client->object()->listObjects($args);
$client->object()->createObject($args);
$client->object()->deleteObject($args);
$client->object()->existObject($args);

临时URL
$client->object()->getPrivateObject($bucket, $key, $expire = 60);
</pre>