<?php
declare(strict_types=1);

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class SymfonyHttpClient implements HttpClient
{
    private HttpClientInterface $httpClient;
    private string $apiHost;

    public function __construct(HttpClientInterface $httpClient, string $apiHost)
    {
        $this->httpClient = $httpClient;
        $this->apiHost = $apiHost;
    }

    public function get(string $uri): Response
    {
        $url = $this->addHost($uri);

        return new SymfonyHttpResponseAdapter($this->httpClient->request('GET', $url));
    }

    public function post(string $uri, array $options): Response
    {
        $url = $this->addHost($uri);

        return new SymfonyHttpResponseAdapter($this->httpClient->request('POST', $url));
    }

    private function addHost(string $uri): string
    {
        return trim($this->apiHost, '/') . '/' . trim($uri, '/');
    }
}
