<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithPageToken;

interface ApiCallerInterface
{
    /**
     * @param string $pageToken
     * @param mixed[] $parameters
     * @return ApiCallerResponse
     */
    public function doFollowUpRequest(string $pageToken, array $parameters): ApiCallerResponse;

    /**
     * @param int|null $limit
     * @param int $offset
     * @param mixed[] $parameters
     * @return ApiCallerResponse
     */
    public function doInitialRequest(?int $limit, int $offset, array $parameters): ApiCallerResponse;

    /**
     * @return int
     */
    public function getResultsPerRequest(): int;
}
