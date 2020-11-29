<?php
declare(strict_types=1);

namespace App\Http;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class SymfonyHttpResponseAdapter implements Response
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getHeaders(bool $throw = true): array
    {
        return $this->response->getHeaders(false);
    }

    public function getContent(): string
    {
        return $this->response->getContent(false);
    }
}
