<?php

namespace Nearata\Cloudflare;

use Flarum\Extend;
use Flarum\Foundation\Event\ClearingCache;
use Flarum\Settings\Event\Saving as SettingsSaving;
use Nearata\Cloudflare\Admin\Middleware\CheckDevelopmentMode;
use Nearata\Cloudflare\Api\Controller\RefreshZoneController;
use Nearata\Cloudflare\Filesystem\R2Driver;
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
        ->add(CheckDevelopmentMode::class),

    (new Extend\Settings)
        ->default('nearata-cloudflare.development-mode', false)
        ->default('nearata-cloudflare.development-mode-time', 0)
        ->default('nearata-cloudflare.zone-id', '')
        ->default('nearata-cloudflare.api-key', '')
        ->default('nearata-cloudflare.r2-bucket-name', '')
        ->default('nearata-cloudflare.r2-access-key-id', '')
        ->default('nearata-cloudflare.r2-access-key-secret', '')
        ->default('nearata-cloudflare.r2-s3-api', '')
        ->default('nearata-cloudflare.r2-public-domain', ''),

    (new Extend\Filesystem)
        ->driver('r2', R2Driver::class),
];
