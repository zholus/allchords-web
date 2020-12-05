<?php
declare(strict_types=1);

namespace App\Http;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class SymfonyHttpClient implements HttpClient
{
    private HttpClientInterface $httpClient;
    private string $apiHost;
    private LoggerInterface $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        string $apiHost,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->apiHost = $apiHost;
        $this->logger = $logger;
    }

    public function get(string $uri, array $options = []): Response
    {
        $url = $this->addHost($uri);

        $this->logger->info('Request', [
            'url' => $url,
            'method' => 'GET',
            'options' => $options
        ]);

        $symfonyResponse = $this->httpClient->request('GET', $url, $options);

        $response = new SymfonyHttpResponseAdapter($symfonyResponse);

        $this->logger->info('Response', [
            'url' => $url,
            'method' => 'GET',
            'options' => $options,
            'response' => [
                'content' => $response->getContent(),
                'status_code' => $response->getStatusCode(),
                'headers' => $response->getHeaders(),
            ]
        ]);

        return $response;
    }

    public function post(string $uri, array $options = []): Response
    {
        $url = $this->addHost($uri);

        $response = new SymfonyHttpResponseAdapter($this->httpClient->request('POST', $url, $options));

        // todo: log request and response;

        return $response;
    }

    private function addHost(string $uri): string
    {
        return trim($this->apiHost, '/') . '/' . trim($uri, '/');
    }
}
