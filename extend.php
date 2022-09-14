<?php

namespace Nearata\Cloudflare;

use Flarum\Extend;
use Flarum\Foundation\Event\ClearingCache;
use Nearata\Cloudflare\Api\Controller\RefreshZoneController;
use Nearata\Cloudflare\Foundation\Listener\ClearingCacheListener;

return [
    (new Extend\Frontend('admin'))
        ->css(__DIR__.'/less/admin.less')
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Event)
        ->listen(ClearingCache::class, ClearingCacheListener::class),

    (new Extend\Routes('api'))
        ->patch('/nearata/cloudflare/refreshZone', 'nearata.cloudflare.refresh-zone', RefreshZoneController::class)
];
