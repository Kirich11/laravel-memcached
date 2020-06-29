<?php

namespace Kirich\LaravelMemcahced\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kirich\LaravelMemcahced\Services\Cache\MemcachedService;
use Throwable;
use Illuminate\Http\Response;
use Kirich\LaravelMemcahced\Services\Serialization\SerializerService;

class CacheViews
{
    public function handle(Request $request, Closure $next)
    {
        if (env('APP_ENV') != 'production') {
            return $next($request);
        } else {
            $memcached = new MemcachedService();
            $seriazer = new SerializerService();
            $fullUrl = $this->clearUrlFromCachePrefix($request);
    
            try {
                /** @var Response $response */
                $response = $next($request);
            } catch (Throwable $t) {
                Log::error($t->getFile() . ' on line ' . $t->getLine() . ' ' . $response->getStatusCode() . ' ' . $t->getMessage());

                // Check for 50X errors
                if ($this->isCritical($response->getStatusCode())) {
                    $backup = $memcached->get($fullUrl, false);
                    if (!empty($backup)) {
                        $backup = $seriazer->unserialize($backup);
                        return $backup;
                    }

                    return $response;
                }

                $cache = $seriazer->serialize($response);
                $memcached->add($fullUrl, $cache);

                return $response;
            }

            $cache = $seriazer->serialize($response);
            $memcached->add($fullUrl, $cache);

            return $response;
        }
    }

    protected function isCritical($code)
    {
        if (is_string($code)) {
            $code = intval($code);
        }

        return $code >= 500;
    }

    protected function clearUrlFromCachePrefix(Request $request): string
    {
        $query = $request->getQueryString();

        $domain = $request->getHttpHost();
        if ($this->checkNoCache($domain)) {
            $urlParts = explode('.', $domain);

            $domain = $urlParts[1].'.'.$urlParts[2];
        }

        $question = $request->getBaseUrl().$request->getPathInfo() === '/' ? '/?' : '?';

        return $query ? $request->getScheme().'://'.$domain.$question.$query : $request->getScheme().'://'.$domain;
    }

    protected function checkNoCache(string $url): bool
    {
        $urlParts = explode('.', $url);

        if (count($urlParts) == 3) {
            if (in_array($urlParts[0], ['n', 'nocache'])) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }
}
