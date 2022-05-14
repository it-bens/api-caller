<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithLimitOffset;

interface ApiCallerInterface
{
    /**
     * @param int $limit
     * @param int $offset
     * @param mixed[] $parameters
     * @return ApiCallerResponse
     */
    public function doRequest(int $limit, int $offset, array $parameters): ApiCallerResponse;

    /**
     * @return int
     */
    public function getResultsPerRequest(): int;
}
