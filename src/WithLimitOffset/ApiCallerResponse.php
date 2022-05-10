<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithLimitOffset;

class ApiCallerResponse
{
    /**
     * @phpstan-ignore-next-line
     * @param array $results
     */
    public function __construct(public array $results)
    {
    }
}
