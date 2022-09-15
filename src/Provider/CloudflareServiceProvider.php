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

        $token = $settings->get('nearata-cloudflare.api-key');
        $zone = $settings->get('nearata-cloudflare.zone-id');

        Factory::macro('cloudflare', function () use ($token, $zone) {
            return (new Factory())
                ->withToken($token)
                ->contentType('application/json')
                ->baseUrl("https://api.cloudflare.com/client/v4/zones/$zone");
        });
    }
}
