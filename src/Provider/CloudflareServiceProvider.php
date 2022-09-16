<?php

namespace Nearata\Cloudflare\Provider;

use Flarum\Foundation\AbstractServiceProvider;
use Illuminate\Http\Client\Factory;

class CloudflareServiceProvider extends AbstractServiceProvider
{
    public function boot()
    {
        /** @var \Flarum\Settings\SettingsRepositoryInterface */
        $settings = $this->container->make('flarum.settings');

        $oldToken = $settings->get('nearata-cloudflare.api-key');
        $oldZone = $settings->get('nearata-cloudflare.zone-id');

        Factory::macro('cloudflare', function ($newToken = null) use ($oldToken) {
            return (new Factory())
                ->withToken($newToken ?? $oldToken)
                ->contentType('application/json')
                ->baseUrl('https://api.cloudflare.com/client/v4');
        });

        Factory::macro('cloudflareZoned', function ($newToken = null, $newZone = null) use ($oldToken, $oldZone) {
            $zone = $newZone ?? $oldZone;

            return (new Factory())
                ->withToken($newToken ?? $oldToken)
                ->contentType('application/json')
                ->baseUrl("https://api.cloudflare.com/client/v4/zones/$zone");
        });
    }
}
