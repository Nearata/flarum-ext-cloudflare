<?php

namespace Nearata\Cloudflare\Adapter;

class AwsS3Adapter extends \League\Flysystem\AwsS3v3\AwsS3Adapter
{
    public function getUrl(string $path): string
    {
        /**
         * @var \Flarum\Settings\SettingsRepositoryInterface
         */
        $settings = resolve('flarum.settings');

        $public = (string) $settings->get('nearata-cloudflare.r2-public-domain');

        return "$public/$path";
    }
}
