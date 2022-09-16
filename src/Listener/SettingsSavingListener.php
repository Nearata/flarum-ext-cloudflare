<?php

namespace Nearata\Cloudflare\Listener;

use Flarum\Foundation\Config;
use Flarum\Foundation\ValidationException;
use Flarum\Settings\Event\Saving;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Http\Client\Factory;
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

        if (!empty($token)) {
            $this->token = $token;
            $this->updateApiToken();
        }

        if (!empty($securityLevel)) {
            $this->updateSecurityLevel($securityLevel);
        }
    }

    private function updateApiToken(): void
    {
        /** @var \Illuminate\Http\Client\Response */
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
        /** @var \Illuminate\Http\Client\Response */
        $response = Factory::cloudflareZoned($this->token, $this->zone)
            ->patch('/settings/security_level', ['value' => $level]);

        if ($response->failed()) {
            $err = $response->collect('errors')->first()['message'];
            throw new ValidationException(['cloudflare' => $err]);
        }
    }
}
