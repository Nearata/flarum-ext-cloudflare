<?php

namespace Nearata\Cloudflare\Api\Controller;

use Flarum\Foundation\ValidationException;
use Flarum\Http\RequestUtil;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Http\Client\Factory;
use Laminas\Diactoros\Response\EmptyResponse;
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

        $token = $this->settings->get('nearata-cloudflare.api-key');

        // verify if token is valid
        $response = (new Factory())
            ->withToken($token)
            ->get('https://api.cloudflare.com/client/v4/user/tokens/verify');

        if ($response->failed()) {
            throw new ValidationException(['cloudflare' => 'Invalid API Key']);
        }

        // get zones
        $response = (new Factory())
            ->withToken($token)
            ->get("https://api.cloudflare.com/client/v4/zones");

        if ($response->failed()) {
            $err = collect($response->collect()->get('errors'))->first();
            throw new ValidationException(['cloudflare' => $err['message']]);
        }

        $host = $request->getUri()->getHost();

        $result = collect($response->collect()->get('result'))
            ->filter(function ($item) use ($host) {
                return $item['name'] == $host;
            })
            ->first();

        if (is_null($result)) {
            throw new ValidationException(['cloudflare' => "No Zone ID found with host $host"]);
        }

        $zoneId = $result['id'];

        $this->settings->set('nearata-cloudflare.zone-id', $zoneId);

        return new EmptyResponse();
    }
}
