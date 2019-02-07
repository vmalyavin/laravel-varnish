<?php

namespace Minter\Varnish\Service;

class CacheManagementDummyService implements CacheManagementServiceInterface
{
    /**
     * Clear cache by any from array of tags
     *
     * @param string[] $tags
     */
    public function clearByTags(array $tags): void
    {
       return;
    }

    /**
     * Clear cache by all array of tags
     *
     * @param string[] $tags
     */
    public function clearByTagsAll(array $tags): void
    {
       return;
    }

    /**
     * Clear cache by single tag
     *
     * @param string $tag
     */
    public function clearByTag(string $tag): void
    {
        return;
    }

    /**
     * Clear all cache
     */
    public function clearAll(): void
    {
        return;
    }
}