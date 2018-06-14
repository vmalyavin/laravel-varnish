<?php

namespace Minter\Varnish\Service;

interface CacheManagementServiceInterface
{
    /**
     * Clear cache by any from array of tags
     *
     * @param string[] $tags
     */
    public function clearByTags(array $tags): void;

    /**
     * Clear cache by all array of tags
     *
     * @param string[] $tags
     */
    public function clearByTagsAll(array $tags): void;

    /**
     * Clear cache by single tag
     *
     * @param string $tag
     */
    public function clearByTag(string $tag): void;

    /**
     * Clear all cache
     */
    public function clearAll(): void;
}