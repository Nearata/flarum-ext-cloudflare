<?php

namespace Nearata\Cloudflare;

use Flarum\Extend;
use Flarum\Foundation\Event\ClearingCache;
use Flarum\Settings\Event\Saving as SettingsSaving;
use Nearata\Cloudflare\Admin\Middleware\CheckDevelopmentMode;
use Nearata\Cloudflare\Api\Controller\RefreshZoneController;
use Nearata\Cloudflare\Listener\ClearingCacheListener;
use Nearata\Cloudflare\Listener\SettingsSavingListener;
use Nearata\Cloudflare\Provider\CloudflareServiceProvider;

return [
    (new Extend\Frontend('admin'))
        ->css(__DIR__.'/less/admin.less')
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Event)
        ->listen(ClearingCache::class, ClearingCacheListener::class)
        ->listen(SettingsSaving::class, SettingsSavingListener::class),

    (new Extend\Routes('api'))
        ->patch('/nearata/cloudflare/refreshZone', 'nearata.cloudflare.refresh-zone', RefreshZoneController::class),

    (new Extend\ServiceProvider)
        ->register(CloudflareServiceProvider::class),

    (new Extend\Middleware('admin'))
        ->add(CheckDevelopmentMode::class)
];
