<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithLimitOffset;

interface ApiCallerInterface
{
    /**
     * @param int $currentLimit
     * @param int $currentOffset
     * @return ApiCallerResponse
     */
    public function doRequest(int $currentLimit, int $currentOffset): ApiCallerResponse;

    /**
     * @return int
     */
    public function getResultsPerRequest(): int;
}
