<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithLimitOffset;

trait ApiCallerWrapper
{
    /**
     * @phpstan-ignore-next-line
     * @param ApiCallerInterface $apiCaller
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function request(ApiCallerInterface $apiCaller, ?int $limit = null, int $offset = 0): array
    {
        $remainingResults = $limit;

        $next = true;
        $results = [];
        while ($next) {
            if (null === $remainingResults || $remainingResults > $apiCaller->getResultsPerRequest()) {
                $limit = $apiCaller->getResultsPerRequest();
            } else {
                $limit = $remainingResults;
            }

            $response = $apiCaller->doRequest($limit, $offset);
            $results = array_merge($results, $response->results);
            $offset += $limit;

            // The request series ends if no results are returned.
            $next = 0 !== count($response->results);

            // The request series ends if enough results were returned.
            if (null !== $remainingResults) {
                $remainingResults -= count($response->results);
                if ($remainingResults <= 0) {
                    $next = false;
                }
            }
        }

        return $results;
    }
}
