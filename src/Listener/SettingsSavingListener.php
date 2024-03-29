<?php

namespace Nearata\Cloudflare\Listener;

use Carbon\Carbon;
use Flarum\Foundation\Config;
use Flarum\Foundation\ValidationException;
use Flarum\Settings\Event\Saving;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Nearata\Cloudflare\Helpers;

class SettingsSavingListener
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
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
        $developmentMode = Arr::get($event->settings, 'nearata-cloudflare.development-mode');

        if (isset($token)) {
            $this->token = $token;
            $this->updateApiToken();
        }

        if (isset($securityLevel)) {
            $this->updateSecurityLevel($securityLevel);
        }

        if (isset($minifyCss)) {
            $this->autoMinify('css', $minifyCss);
        }

        if (isset($minifyHtml)) {
            $this->autoMinify('html', $minifyHtml);
        }

        if (isset($minifyJs)) {
            $this->autoMinify('js', $minifyJs);
        }

        if (isset($developmentMode)) {
            $this->updateDevelopmentMode($developmentMode);
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

        $this->zone = Helpers::findZone($this->token, $this->config->url()->getHost());

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
        /** @var Response */
        $response = Factory::cloudflareZoned($this->token, $this->zone)
            ->patch('/settings/minify', [
                'value' => [$key => $value ? 'on' : 'off'],
            ]);

        $response->onError([$this, 'onError']);
    }

    private function updateDevelopmentMode(bool $value): void
    {
        /** @var Response */
        $response = Factory::cloudflareZoned($this->token, $this->zone)
            ->patch('/settings/development_mode', ['value' => $value ? 'on' : 'off']);

        $response->onError([$this, 'onError']);

        if ($value) {
            $this->settings->set('nearata-cloudflare.development-mode-time', Carbon::now()->getTimestamp());
        }
    }

    public function onError(Response $response): void
    {
        $err = $response->collect('errors')->first()['message'];
        throw new ValidationException(['cloudflare' => $err]);
    }
}
