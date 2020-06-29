<?php

namespace Kirich\LaravelMemcahced\Services\Serialization;

use Illuminate\Http\Response;

/**
 * Props to https://github.com/spatie/laravel-responsecache for serialization idea
 * 
 * @inheritdoc
 */
class SerializerService implements SerializerInterface
{
    public function serialize(Response $response): string
    {
        return serialize($this->getData($response));
    }

    public function unserialize(string $serialized): Response
    {
        return $this->buildResponse(unserialize($serialized));
    }

    protected function getData(Response $response): array
    {
        $statusCode = $response->getStatusCode();
        $headers = $response->headers;
        $content = $response->getContent();

        return compact('statusCode', 'headers', 'content');
    }

    protected function buildResponse(array $responseParams): Response
    {
        return new Response($responseParams['content'], $responseParams['statusCode'], $responseParams['headers']);
    }
}