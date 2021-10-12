<?php

namespace Cerpus\Edlib\ApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;

class EdlibApiClient extends Client
{
    public function __construct(array $config = [])
    {
        parent::__construct(
            array_replace(
                [
                    'base_uri' => 'https://api.edlib.com/',
                ],
                $config
            )
        );
    }

    public function postAsync(string $uri, array $options = []): PromiseInterface
    {
        return parent::postAsync($uri, $options);
    }
}
