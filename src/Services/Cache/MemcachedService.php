<?php

namespace Kirich\LaravelMemcahced\Services\Cache;

use Exception;
use Illuminate\Support\Facades\Config;

class MemcachedService implements CacheInterface
{
    protected $primary;

    protected $backup;

    public function __construct()
    {
        if (!Config::has('memcached')) {
            throw new Exception('no memcached config'.PHP_EOL.'please run php artisan vendor:publish');
        }
        $config = config('memcached');

        $this->primary = new \Memcached('primary');
        $this->backup = new \Memcached('backup');
        if (count($this->primary->getServerList()) < 1) {
            $this->primary->addServer(
                $config['servers']['primary']['server'],
                $config['servers']['primary']['port'],
                $config['servers']['primary']['weight']
            );
        }
        if (count($this->backup->getServerList()) < 1) {
            $this->backup->addServer(
                $config['servers']['backup']['server'],
                $config['servers']['backup']['port'],
                $config['servers']['backup']['weight']
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function add(string $url, string $response, bool $isPrimary = true)
    {
        $cacheInstances = [$this->backup];

        if ($isPrimary) {
            $cacheInstances[] = $this->backup;
        }

        foreach ($cacheInstances as $cacheInstance) {
            do {
                $cache = $cacheInstance->get($url, null, \Memcached::GET_EXTENDED);
                $cas = $cache['cas'];
                if ($cacheInstance->getResultCode() == \Memcached::RES_NOTFOUND) {
                    $cacheInstance->add($url, $response);
                } else {
                    $cacheInstance->cas($cas, $url, $response);
                }
            } while ($cacheInstance->getResultCode() != \Memcached::RES_SUCCESS);
        }
    }

    /**
     * @inheritdoc
     */
    public function get(string $url, bool $isPrimary = true)
    {
        if ($isPrimary) {
            $cache = $this->primary->get($url);
        } else {
            $cache = $this->backup->get($url);
        }

        return $cache;
    }

    /**
     * @inheritdoc
     */
    public function delete(string $url, bool $isPrimary)
    {
        $cacheInstances = [$this->backup];

        if ($isPrimary) {
            $cacheInstances[] = $this->backup;
        }

        foreach ($cacheInstances as $cacheInstance) {
            $cacheInstance->delete($url);
        }
    }
}
