<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithPageToken;

interface ApiCallerInterface
{
    /**
     * @param string $pageToken
     * @return ApiCallerResponse
     */
    public function doFollowUpRequest(string $pageToken): ApiCallerResponse;

    /**
     * @param int|null $limit
     * @param int $offset
     * @return ApiCallerResponse
     */
    public function doInitialRequest(?int $limit = null, int $offset = 0): ApiCallerResponse;

    /**
     * @return int
     */
    public function getResultsPerRequest(): int;
}
