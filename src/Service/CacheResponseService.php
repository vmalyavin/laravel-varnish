<?php

namespace Minter\Varnish\Service;

use Illuminate\Http\Response;

class CacheResponseService implements CacheResponseServiceInterface
{
    /**
     * Response cache tags array
     *
     * @var array
     */
    protected $responseTags = [];

    /**
     * Cache ttl runtime setting
     *
     * @var array
     */
    protected $cacheTtl = null;

    /**
     * Set response cache ttl while runtime
     *
     * @param int $cacheTtl
     */
    public function setCacheTtl(int $cacheTtl): void
    {
        $this->cacheTtl = $cacheTtl;
    }

    /**
     * Add multiple tags to response cache
     *
     * @param string[]|array $tags
     */
    public function addTags(array $tags): void
    {
        array_merge($this->responseTags, $tags);
    }

    /**
     * Add tag to response cache
     *
     * @param string $tag
     */
    public function addTag(string $tag): void
    {
        if (empty($tag)) {
            return;
        }
        $this->responseTags[] = $tag;
    }

    /**
     * Get current response tags array
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->responseTags;
    }

    /**
     * Add cache headers to response
     *
     * @param Response    $response
     * @param string|null $cacheTtl
     *
     * @return Response
     */
    public function addCacheHeadersToResponse(Response $response, string $cacheTtl = null): Response
    {
        /**
         * Response cache TTL priority:
         * 1) Runtime TTL @see  ResponseCacheService::setCacheTtl()
         * 2) TTL value from middleware param @see ResponseCacheServiceInterface::TIME_TEMPLATES
         * 3) TTL value from middleware as seconds value
         * 4) Default TTL from config
         */
        $cacheTtl = $this->cacheTtl ?? ((isset(static::TIME_TEMPLATES[$cacheTtl])) ? static::TIME_TEMPLATES[$cacheTtl] : ((int)$cacheTtl > 0 ? (int)$cacheTtl : config('varnish.default_cache_ttl')));
        $headers  = [config('varnish.cache_header_time') => $cacheTtl];

        $tags = array_map(
            function ($tag) {
                return trim((string)$tag);
            }, $this->responseTags
        );
        $tags = array_unique(array_filter($tags));

        if (empty($tags)) {
            return $response->withHeaders($headers);
        }

        sort($tags);
        $headers[config('varnish.cache_header_tags')] = 'g' . static::TAG_SEP . implode(static::TAG_SEP,
                $tags) . static::TAG_SEP;

        return $response->withHeaders($headers);
    }
}