<?php
declare(strict_types=1);

namespace App\Http;

interface HttpClient
{
    public function get(string $uri): Response;
    public function post(string $uri, array $options): Response;
}
