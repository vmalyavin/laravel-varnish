<?php

namespace Minter\Varnish\Service;

use Symfony\Component\HttpFoundation\Response;

interface CacheResponseServiceInterface
{
    /**
     * Cache tags separator in header string
     */
    public const TAG_SEP = ',';

    /**
     * Time templates for middleware parameter
     */
    public const TIME_TEMPLATES = [
        '15min' => 900,
        '30min' => 1800,
        'hour'  => 3600,
        'day'   => 86400,
        'week'  => 604800,
        'month' => 2592000,
        'year'  => 31556926,
    ];

    /**
     * Set response cache ttl while runtime
     *
     * @param int $cacheTtl
     */
    public function setCacheTtl(int $cacheTtl): void;

    /**
     * Add multiple tags to response cache
     *
     * @param array $tags
     */
    public function addTags(array $tags): void;

    /**
     * Add tag to response cache
     *
     * @param string $tag
     */
    public function addTag(string $tag): void;

    /**
     * Get current response tags array
     *
     * @return array
     */
    public function getTags(): array;

    /**
     * Add cache headers to response
     *
     * @param \Illuminate\Http\Response|\Illuminate\Http\JsonResponse $response
     * @param string|null                                             $cacheTtl
     *
     * @return Response
     */
    public function addCacheHeadersToResponse($response, string $cacheTtl = null): Response;
}