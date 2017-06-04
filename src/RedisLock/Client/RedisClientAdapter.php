<?php
namespace RedisLock\Client;

use RedisClient\Exception\ErrorResponseException;
use RedisClient\RedisClient;

class RedisClientAdapter implements RedisClientInterface
{
    protected $client;

    public function __construct(RedisClient $client)
    {
        $this->client = $client;
    }

    public function set($key, $value, $expireResolution = null, $expireTTL = null, $flag = null)
    {
        $seconds = null;
        $milliseconds = null;

        if (!is_null($expireTTL)) {
            if ($expireResolution == 'PX') {
                $milliseconds = $expireTTL;
            } else {
                $seconds = $expireTTL;
            }
        }
        return $this->client->set($key, $value, $seconds, $milliseconds, $flag);
    }

    public function get($key)
    {
        return $this->client->get($key);
    }

    public function execLua($script, $sha1, $keys = null, $args = null)
    {
        try {
            return $this->client->evalsha($sha1, $keys, $args);
        } catch (ErrorResponseException $Ex) {
            if (0 === strpos($Ex->getMessage(), 'NOSCRIPT')) {
                return $this->client->eval($script, $keys, $args);
            }
            throw $Ex;
        }
    }

    public function del($keys)
    {
        return $this->client->del($keys);
    }
}