<?php

namespace Nearata\Cloudflare\Listener;

use Flarum\Foundation\Config;
use Flarum\Foundation\ValidationException;
use Flarum\Settings\Event\Saving;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Nearata\Cloudflare\Utils;

class SettingsSavingListener
{
    protected $config;
    protected $settings;

    protected $token;
    protected $zone;

    public function __construct(Config $config, SettingsRepositoryInterface $settings)
    {
        $this->config = $config;
        $this->settings = $settings;
    }

    public function handle(Saving $event)
    {
        $token = Arr::get($event->settings, 'nearata-cloudflare.api-key');
        $securityLevel = Arr::get($event->settings, 'nearata-cloudflare.security-level');
        $minifyCss = Arr::get($event->settings, 'nearata-cloudflare.minify-css');
        $minifyHtml = Arr::get($event->settings, 'nearata-cloudflare.minify-html');
        $minifyJs = Arr::get($event->settings, 'nearata-cloudflare.minify-js');
        $browserCacheTtl = Arr::get($event->settings, 'nearata-cloudflare.browser-cache-ttl');

        if (!empty($token)) {
            $this->token = $token;
            $this->updateApiToken();
        }

        if (!empty($securityLevel)) {
            $this->updateSecurityLevel($securityLevel);
        }

        if (!empty($minifyCss)) {
            $this->autoMinify('css', $minifyCss);
        }

        if (!empty($minifyHtml)) {
            $this->autoMinify('html', $minifyHtml);
        }

        if (!empty($minifyJs)) {
            $this->autoMinify('js', $minifyJs);
        }

        if (!empty($browserCacheTtl)) {
            $this->updateBrowserCacheTtl($browserCacheTtl);
        }
    }

    private function updateApiToken(): void
    {
        /** @var Response */
        $response = Factory::cloudflare($this->token)
            ->get('/user/tokens/verify');

        if ($response->failed()) {
            throw new ValidationException(['cloudflare' => 'Invalid API Key']);
        }

        $this->zone = Utils::findZone($this->token, $this->config->url()->getHost());

        $this->settings->set('nearata-cloudflare.zone-id', $this->zone);
    }

    private function updateSecurityLevel(string $level): void
    {
        /** @var Response */
        $response = Factory::cloudflareZoned($this->token, $this->zone)
            ->patch('/settings/security_level', ['value' => $level]);

        $response->onError([$this, 'onError']);
    }

    private function autoMinify(string $key, bool $value): void
    {
        $value = $value ? 'on' : 'off';

        /** @var Response */
        $response = Factory::cloudflareZoned($this->token, $this->zone)
            ->patch('/settings/minify', [$key => $value]);

        $response->onError([$this, 'onError']);
    }

    private function updateBrowserCacheTtl(string $value): void
    {
        /** @var Response */
        $response = Factory::cloudflareZoned($this->token, $this->zone)
            ->patch('/settings/browser_cache_ttl', ["value" => intval($value)]);

        $response->onError([$this, 'onError']);
    }

    public function onError(Response $response): void
    {
        $err = $response->collect('errors')->first()['message'];
        throw new ValidationException(['cloudflare' => $err]);
    }
}
