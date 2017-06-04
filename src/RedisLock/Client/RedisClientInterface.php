<?php
namespace RedisLock\Client;

interface RedisClientInterface
{
    /**
     * SET key value [EX seconds | PX milliseconds] [NX|XX]
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/set
     *
     * @param $key
     * @param $value
     * @param null $expireResolution
     * @param null $expireTTL
     * @param null $flag
     * @return mixed
     */
    public function set($key, $value, $expireResolution = null, $expireTTL = null, $flag = null);

    /**
     * GET key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/get
     *
     * @param string $key
     * @return string|null
     */
    public function get($key);

    /**
     * Execute lua script
     * @param string $script
     * @param string $sha1
     * @param string[]|null $keys
     * @param string[]|null $args
     * @return mixed
     * @throws \Exception
     */
    public function execLua($script, $sha1, $keys = null, $args = null);


    /**
     * @param array $keys
     * @return mixed
     */
    public function del($keys);
}