<?php

namespace Nearata\Cloudflare\Listener;

use Flarum\Foundation\Event\ClearingCache;
use Illuminate\Http\Client\Factory;

class ClearingCacheListener
{
    public function handle(ClearingCache $event)
    {
        Factory::cloudflareZoned()
            ->post('/purge_cache', ['purge_everything' => true]);
    }
}
