<?php

namespace Kirich\LaravelMemcached\Services\Cache;

use Illuminate\Http\Response;

interface CacheInterface
{
    /**
     * Add response to cache. If key is set, compares and sets new response in cache
     * 
     * @param string $url cache key
     * @param string $response serialized cache value
     * @param bool $isPrimary determines if the method works only with backup. If true - works with both instances (primary & backup).
     */
    public function add(string $url, string $response, bool $isPrimary);
    
    /**
     * Delete response from cache
     * 
     * @param string $url cache key
     * @param bool $isPrimary determines if the method works only with backup. If true - works with both instances (primary & backup). 
     */
    public function delete(string $url, bool $isPrimary);

    /**
     * Load response from cache
     * 
     * @param string $url cache key
     * @param bool $isPrimary determines if the method works only with backup. If true - works with both instances (primary & backup).
     * 
     * @return string serialized cached value
     */
    public function get(string $url, bool $isPrimary) : string;
}