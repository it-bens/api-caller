<?php

declare(strict_types=1);

namespace ITB\ApiCaller\Tests\WithLimitOffset;

use ITB\ApiCaller\WithLimitOffset\ApiCallerInterface;
use ITB\ApiCaller\WithLimitOffset\ApiCallerResponse;

final class ApiCallerMock implements ApiCallerInterface
{
    /** @phpstan-ignore-next-line */
    private array $items = [];

    public function __construct(private int $itemCount, private int $maxResultsPerRequest)
    {
        for ($i = 1; $i <= $this->itemCount; $i++) {
            $this->items[] = 'I\'m number ' . $i . '!';
        }
    }

    public function doRequest(int $limit, int $offset): ApiCallerResponse
    {
        $results = array_slice($this->items, $offset, $limit);

        return new ApiCallerResponse($results);
    }

    public function getResultsPerRequest(): int
    {
        return $this->maxResultsPerRequest;
    }
}
