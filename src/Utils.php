<?php

namespace Nearata\Cloudflare;

use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Collection;

class Utils
{
    public static function sendApiRequest(string $endpoint = '', array $data = [], bool $update = true): Collection
    {
        /** @var \Flarum\Settings\SettingsRepositoryInterface */
        $settings = resolve(SettingsRepositoryInterface::class);

        $apiKey = $settings->get('nearata-cloudflare.api-key');

        if (is_null($apiKey) || empty($apiKey)) {
            return collect();
        }

        $apiUrl = 'https://api.cloudflare.com/client/v4/zones';

        $request = (new Factory())
            ->withToken($apiKey)
            ->contentType('application/json');

        if ($update) {
            $zoneId = $settings->get('nearata-cloudflare.zone-id');

            if (is_null($zoneId) || empty($zoneId)) {
                return collect();
            }

            $apiUrl += "/$zoneId$endpoint";

            return $request->post($apiUrl, $data)->collect();
        }

        return $request->get($apiUrl, $data)->collect();
    }
}
