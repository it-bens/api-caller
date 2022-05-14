<?php

declare(strict_types=1);

namespace ITB\ApiCaller\WithPageToken;

trait ApiCallerWrapper
{
    /**
     * @param ApiCallerInterface $apiCaller
     * @param int|null $limit
     * @param int $offset
     * @param mixed[] $parameters
     * @return mixed[]
     */
    public function request(ApiCallerInterface $apiCaller, ?int $limit = null, int $offset = 0, array $parameters = []): array
    {
        $remainingResults = $limit;
        if (null === $remainingResults || $remainingResults > $apiCaller->getResultsPerRequest()) {
            $limit = $apiCaller->getResultsPerRequest();
        }

        $response = $apiCaller->doInitialRequest($limit, $offset, $parameters);
        $results = $response->results;
        $nextPageToken = $response->nextPageToken;
        $remainingResults -= count($response->results);

        // The request series ends immediately if no next-page-token is provided.
        $next = null !== $nextPageToken;
        while ($next) {
            // $nextPageToken cannot be null at this point because the loop wouldn't have started if otherwise.
            /** @var string $nextPageToken */
            $response = $apiCaller->doFollowUpRequest($nextPageToken, $parameters);
            $results = array_merge($results, $response->results);
            $nextPageToken = $response->nextPageToken;

            // The request series ends if no next-page-token is provided.
            $next = null !== $nextPageToken;

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
