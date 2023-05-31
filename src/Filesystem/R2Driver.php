<?php

namespace Nearata\Cloudflare\Filesystem;

use Aws\S3\S3Client;
use Flarum\Filesystem\DriverInterface;
use Flarum\Foundation\Config;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Filesystem\FilesystemAdapter;
use Laminas\Diactoros\Uri;
use League\Flysystem\Filesystem;
use Nearata\Cloudflare\Adapter\AwsS3Adapter;

class R2Driver implements DriverInterface
{
    public function build(string $diskName, SettingsRepositoryInterface $settings, Config $config, array $localConfig): Cloud
    {
        $uri = new Uri($settings->get('nearata-cloudflare.r2-s3-api'));

        /**
         * we need to strip the path
         *
         * @todo: better way?
         */
        $endpoint = $uri->getScheme().'://'.$uri->getHost();

        $client = new S3Client([
            'credentials' => [
                'key' => $settings->get('nearata-cloudflare.r2-access-key-id'),
                'secret' => $settings->get('nearata-cloudflare.r2-access-key-secret'),
            ],
            'region' => 'auto',
            'endpoint' => $endpoint,
            'version' => 'latest',
            'use_path_style_endpoint' => true,
        ]);

        $adapter = new AwsS3Adapter($client, $settings->get('nearata-cloudflare.r2-bucket-name'));

        return new FilesystemAdapter(new Filesystem($adapter));
    }
}
