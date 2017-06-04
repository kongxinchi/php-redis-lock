<?php
namespace RedisLock\Client;


use Predis\Client;
use Predis\Response\ServerException;

class PredisAdapter implements RedisClientInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function set($key, $value, $expireResolution = null, $expireTTL = null, $flag = null)
    {
        return $this->client->set($key, $value, $expireResolution, $expireTTL, $flag);
    }

    public function get($key)
    {
        return $this->client->get($key);
    }

    public function execLua($script, $sha1, $keys = null, $args = null)
    {
        if (is_null($keys)) {
            $keys = [];
        }
        if (is_null($args)) {
            $args = [];
        }
        try {
            return call_user_func_array(
                [$this->client, 'evalsha'],
                array_merge([$sha1, count($keys)], $keys, $args)
            );
        } catch (ServerException $e) {
            if (0 === strpos($e->getMessage(), 'NOSCRIPT')) {
                return call_user_func_array(
                    [$this->client, 'eval'],
                    array_merge([$script, count($keys)], $keys, $args)
                );
            }
            throw $e;
        }
    }

    public function del($keys)
    {
        return $this->client->del($keys);
    }
}