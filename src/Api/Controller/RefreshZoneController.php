<?php

namespace Nearata\Cloudflare\Api\Controller;

use Flarum\Foundation\ValidationException;
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

        $response = Utils::sendApiRequest('', [], false);
        $host = $request->getUri()->getHost();

        if (!$response['success']) {
            $err = collect($response->get('errors'))
                ->map(function ($item) {
                    return $item['message'];
                })
                ->first();

            throw new ValidationException(['cloudflare' => $err]);
        }

        $result = collect($response->get('result'))
            ->filter(function ($item) use ($host) {
                return $item['name'] == $host;
            })
            ->first();

        if (is_null($result)) {
            throw new ValidationException(['cloudflare' => "No Zone ID found with host $host"]);
        }

        $zoneId = $result['id'];

        $this->settings->set('nearata-cloudflare.zone-id', $zoneId);

        return new EmptyResponse(200);
    }
}
