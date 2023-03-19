<?php

namespace Nearata\Cloudflare;

use Flarum\Foundation\ValidationException;
use Illuminate\Http\Client\Factory;

class Helpers
{
    public static function findZone(string $token = null, string $host): ?string
    {
        /** @var \Illuminate\Http\Client\Response */
        $response = Factory::cloudflare($token)
            ->get('/zones');

        if ($response->failed()) {
            $err = $response->collect('errors')->first()['message'];
            throw new ValidationException(['cloudflare' => $err]);
        }

        $zone = $response->collect('result')
            ->filter(function ($item) use ($host) {
                return $item['name'] == $host;
            })
            ->first();

        if (is_null($zone)) {
            throw new ValidationException(['cloudflare' => "No Zone ID found with host $host"]);
        }

        return $zone['id'];
    }
}
