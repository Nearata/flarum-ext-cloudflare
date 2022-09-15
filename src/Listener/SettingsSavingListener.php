<?php

namespace Nearata\Cloudflare\Listener;

use Flarum\Foundation\ValidationException;
use Flarum\Settings\Event\Saving;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Arr;

class SettingsSavingListener
{
    public function handle(Saving $event)
    {
        $securityLevel = Arr::get($event->settings, 'nearata-cloudflare.security-level');

        if (!is_null($securityLevel)) {
            $this->updateSecurityLevel($securityLevel);
        }
    }

    private function updateSecurityLevel(string $level) {
        /** @var \Illuminate\Http\Client\Response */
        $response = Factory::cloudflare()
            ->patch('/settings/security_level', ['value' => $level]);

        if ($response->failed()) {
            $err = $response->collect('errors')->first()['message'];
            throw new ValidationException(['cloudflare' => $err]);
        }
    }
}
