<?php
declare(strict_types=1);

namespace App\Http;

interface Response
{
    public function getStatusCode(): int;
    public function getHeaders(): array;
    public function getContent(): string;
}
