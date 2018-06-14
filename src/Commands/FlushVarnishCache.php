<?php

namespace Minter\Varnish\Commands;

use Illuminate\Console\Command;
use Minter\Varnish\Service\CacheManagementServiceInterface;

class FlushVarnishCache extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'varnish:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the varnish cache.';

    /**
     * @param CacheManagementServiceInterface $cacheManagementService
     */
    public function handle(
        CacheManagementServiceInterface $cacheManagementService
    ) {
        $cacheManagementService->clearAll();
        $this->comment('The varnish cache has been flushed!');
    }
}