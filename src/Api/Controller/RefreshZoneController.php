<?php

namespace Nearata\Cloudflare\Api\Controller;

use Flarum\Http\RequestUtil;
use Flarum\Settings\SettingsRepositoryInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Nearata\Cloudflare\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RefreshZoneController implements RequestHandlerInterface
{
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);

        $actor->assertAdmin();

        $host = $request->getUri()->getHost();

        $zoneId = Utils::findZone(null, $host);

        $this->settings->set('nearata-cloudflare.zone-id', $zoneId);

        return new EmptyResponse();
    }
}
