<?php

namespace Nearata\Cloudflare\Admin\Middleware;

use Carbon\Carbon;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Http\Client\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckDevelopmentMode implements MiddlewareInterface
{
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $handle = $handler->handle($request);

        $developmentMode = (bool) $this->settings->get('nearata-cloudflare.development-mode');

        if (!$developmentMode) {
            return $handle;
        }

        $time = $this->settings->get('nearata-cloudflare.development-mode-time');

        if (Carbon::now()->diffInHours(Carbon::createFromTimestamp($time)) < 3) {
            return $handle;
        }

        /** @var \Illuminate\Http\Client\Response */
        $response = Factory::cloudflareZoned()
            ->get('/settings/development_mode');

        if ($response->successful()) {
            $value = $response->collect('result')->get('value');

            if ($value === 'off') {
                $this->settings->set('nearata-cloudflare.development-mode', false);
                $this->settings->set('nearata-cloudflare.development-mode-time', null);
            }
        }

        return $handle;
    }
}
