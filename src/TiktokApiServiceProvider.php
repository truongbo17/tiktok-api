<?php

namespace TruongBo\TiktokApi;

use GuzzleHttp\Client;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use TruongBo\TiktokApi\Facades\TiktokApiFacade;

class TiktokApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        // register the helper functions
        $this->loadHelpers();
        // facade
        $client = new Client($this->getConfigGuzzle());
        $this->app->singleton('tiktok-api', fn() => new Tiktok(client: $client));
    }

    public function loadHelpers()
    {
        require_once __DIR__ . '/helpers.php';
    }

    public function getConfigGuzzle(): array
    {
        return [
            'timeout'         => 5000,
            'verify'          => false,
            'headers'         => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
                'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            ],
            'http_errors'     => false,
            'allow_redirects' => [
                'track_redirects' => true
            ],
            'cookies'         => true
        ];
    }
}
