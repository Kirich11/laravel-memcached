<?php

namespace Kirich\LaravelMemcached\Services\Serialization;

use Illuminate\Http\Response;

interface SerializerInterface
{
    /**
     * Serialize data for cache storage
     * 
     * @param Response $response
     * 
     * @return string
     */
    public function serialize(Response $response): string;

    /**
     * Unserialize data to create response
     * 
     * @param string $serialized
     * 
     * @return Response
     */
    public function unserialize(string $serialized): Response;
}