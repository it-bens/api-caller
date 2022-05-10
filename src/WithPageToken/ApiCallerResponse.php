<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithPageToken;

class ApiCallerResponse
{
    /**
     * @phpstan-ignore-next-line
     * @param array $results
     * @param string|null $nextPageToken
     */
    public function __construct(public array $results, public ?string $nextPageToken = null)
    {
    }
}
