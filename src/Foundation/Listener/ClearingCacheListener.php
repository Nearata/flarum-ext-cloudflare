<?php

namespace Nearata\Cloudflare\Foundation\Listener;

use Flarum\Foundation\Event\ClearingCache;
use Nearata\Cloudflare\Utils;

class ClearingCacheListener
{
    public function handle(ClearingCache $event)
    {
        Utils::sendApiRequest('/purge-cache', ['purge_everything' => true]);
    }
}
