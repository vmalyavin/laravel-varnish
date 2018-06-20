<?php

namespace Minter\Varnish\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Minter\Varnish\Service\CacheResponseServiceInterface;

class AddCacheHeaders
{
    /**
     * @var CacheResponseServiceInterface
     */
    protected $cacheResponseService;

    /**
     * AddCacheHeaders constructor.
     *
     * @param CacheResponseServiceInterface $cacheResponseService
     */
    public function __construct(CacheResponseServiceInterface $cacheResponseService)
    {
        $this->cacheResponseService = $cacheResponseService;
    }

    /**
     * @param             $request
     * @param Closure     $next
     * @param string|null $cacheTtl
     *
     * @return Response
     */
    public function handle($request, Closure $next, string $cacheTtl = null)
    {
        /*** @var \Illuminate\Http\Response|\Illuminate\Http\JsonResponse $response */
        $response = $next($request);

        return $this->cacheResponseService->addCacheHeadersToResponse($response, $cacheTtl);
    }
}
