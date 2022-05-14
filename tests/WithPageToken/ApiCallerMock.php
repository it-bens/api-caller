<?php

declare(strict_types=1);

namespace ITB\ApiCaller\Tests\WithPageToken;

use ITB\ApiCaller\WithPageToken\ApiCallerInterface;
use ITB\ApiCaller\WithPageToken\ApiCallerResponse;

final class ApiCallerMock implements ApiCallerInterface
{
    /** @var mixed[] */
    private array $items = [];
    /** @var array<string, mixed> $pages */
    private array $pages = [];

    public function __construct(private int $itemCount, private int $maxResultsPerRequest)
    {
        for ($i = 1; $i <= $this->itemCount; $i++) {
            $this->items[] = 'I\'m number ' . $i . '!';
        }
    }

    public function getResultsPerRequest(): int
    {
        return $this->maxResultsPerRequest;
    }

    public function doFollowUpRequest(string $pageToken, array $parameters): ApiCallerResponse
    {
        $results = $this->pages[$pageToken];

        while (key($this->pages) !== $pageToken && key($this->pages) !== null) {
            next($this->pages);
        }
        next($this->pages);
        $nextPageToken = key($this->pages);

        return new ApiCallerResponse($results, $nextPageToken);
    }

    public function doInitialRequest(?int $limit, int $offset, array $parameters): ApiCallerResponse
    {
        $limit = (null === $limit || $limit > $this->maxResultsPerRequest) ? $this->maxResultsPerRequest : $limit;

        while ($offset < count($this->items)) {
            $results = array_slice($this->items, $offset, $limit);

            $pageToken = md5(implode('', $results));
            $this->pages[$pageToken] = $results;

            $offset += count($results);
        }

        $initialPage = reset($this->pages);
        $initialPage = $initialPage ?: [];

        next($this->pages);
        /** @var string|null $nextPageToken */
        $nextPageToken = key($this->pages);

        return new ApiCallerResponse($initialPage, $nextPageToken);
    }
}
