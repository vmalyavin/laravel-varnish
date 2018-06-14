<?php

namespace Minter\Varnish\Service;

use GuzzleHttp\Client;

class CacheManagementService implements CacheManagementServiceInterface
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Clear cache by any from array of tags
     *
     * @param string[] $tags
     */
    public function clearByTags(array $tags): void
    {
        sort($tags);
        $pattern = implode('|', $tags);
        $s       = CacheResponseService::TAG_SEP;
        $options = [
            'headers' => [
                'x-tag'    => '^g.*' . $s . '(' . $pattern . ')' . $s,
                'x-method' => 'BAN'
            ]
        ];

        $this->sendRequest("HEAD", "/cache.txt", $options);
    }

    /**
     * Clear cache by all array of tags
     *
     * @param string[] $tags
     */
    public function clearByTagsAll(array $tags): void
    {
        sort($tags);
        $s       = CacheResponseService::TAG_SEP;
        $pattern = implode($s . '.*' . $s, $tags);
        $options = [
            'headers' => [
                'x-tag'    => '^g.*' . $s . '(' . $pattern . ')' . $s,
                'x-method' => 'BAN'
            ]
        ];

        $this->sendRequest("HEAD", "/cache.txt", $options);
    }

    /**
     * Clear cache by single tag
     *
     * @param string $tag
     */
    public function clearByTag(string $tag): void
    {
        $this->clearByTags([$tag]);
    }

    /**
     * Clear all cache
     */
    public function clearAll(): void
    {
        // @TODO:NEED flush only current app caches
        $options = ['headers' => ['x-method' => 'BAN_ALL']];
        $this->sendRequest("HEAD", "/cache.txt", $options);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @throws \Exception
     */
    private function sendRequest(string $method, string $url, array $options = [])
    {
        try {
            $this->httpClient->requestAsync($method, $url, $options);
        } catch (\Exception $e) {
            $options = json_encode($options, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            throw new \Exception(
                "Error while clearing the cache for the tag. Options: {$options}" . PHP_EOL .
                "Error is: {$e->getMessage()}." . PHP_EOL
                , 0, $e);
        }
    }
}