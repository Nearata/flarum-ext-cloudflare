<?php

namespace Nearata\Cloudflare\Foundation\Listener;

use Flarum\Foundation\Event\ClearingCache;
use Illuminate\Http\Client\Factory;

class ClearingCacheListener
{
    public function handle(ClearingCache $event)
    {
        Factory::cloudflare()->post('/purge_cache', ['purge_everything' => true]);
    }
}
