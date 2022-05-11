<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithLimitOffset;

interface ApiCallerInterface
{
    /**
     * @param int $limit
     * @param int $offset
     * @return ApiCallerResponse
     */
    public function doRequest(int $limit, int $offset): ApiCallerResponse;

    /**
     * @return int
     */
    public function getResultsPerRequest(): int;
}
