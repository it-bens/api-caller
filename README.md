#  API Caller

![Maintenance Status](https://img.shields.io/badge/Maintained%3F-yes-green.svg)
![CI Status](https://github.com/it-bens/api-caller/actions/workflows/ci.yaml/badge.svg?branch=master)
[![codecov](https://codecov.io/gh/it-bens/api-caller/branch/master/graph/badge.svg?token=B39XLZT3DL)](https://codecov.io/gh/it-bens/api-caller)

## What can the API caller do for me?
Many commercial APIs implement some kind of paging or result limits.
But sometimes getting all available records from an API (restricted by query parameters) is necessary.
That's were the API caller steps in.

Instead of doing multiple requests manually, the API caller does that. 
It supports APIs with limit and offset parameters as well as APIs with page tokens.
In order to keep the usage flexible, the API caller leaves the actual API usage to the developer.
It only strips away the repeating logic of performing API requests in a loop till the required results are gathered.

## How to install this package?
The package can be installed via Composer:
```bash
composer require it-bens/api-caller
```
It requires at least PHP 8. It was tests against PHP 8.1 as well.

## What does the usage look like?
First, you have to determine if the API you would like to call uses limit and offset or page tokens.
In both cases a specific API caller implementing the `ApiCallerInterface` has to be created.

### API with limit and offset
```php
use ITB\ApiCaller\WithLimitOffset\ApiCallerInterface;
use ITB\ApiCaller\WithLimitOffset\ApiCallerResponse;

class SpecificApiCaller implements ApiCallerInterface
{
    private const MAX_RESULTS_PER_REQUEST = 10;

    public function __construct(private string $parameter1, private int $parameter2) {}

    public function doRequest(int $limit, int $offset): ApiCallerResponse
    {
        $items = doSomeApiCall([
            'limit' => $limit,
            'offset' => $offset,
            'parameter1' => $this->parameter1,
            'parameter2' => $this->parameter2
        ]);
        return new ApiCallerResponse($items);
    }

    /**
     * @return int
     */
    public function getResultsPerRequest(): int
    {
        return self::MAX_RESULTS_PER_REQUEST;
    }
}
```

The API caller is used with the `ApiCallerWrapper`. It's a trait with only one method.
```php
use ITB\ApiCaller\WithLimitOffset\ApiCallerWrapper;

class SomeService
{
    use ApiCallerWrapper;

    public function someMethode()
    {
        $apiCaller = new SpecificApiCaller('This is the way!', 1337);
        
        $items = $this->request($apiCaller, null, 10);
        // OR:
        $items = $this->request($apiCaller, 100, 10);
        
        // ...
    }
}
```

### API with page tokens
The `ApiCallerInterface` for APIs with page tokens requires two methods implementing API calls.
A method for an initial request, which passes a limit and an offset to the actual API (used for generating the pages)
and a method to handle the requests with page tokens.

```php
use ITB\ApiCaller\WithPageToken\ApiCallerInterface;
use ITB\ApiCaller\WithPageToken\ApiCallerResponse;

class AnotherSpecificApiCaller implements ApiCallerInterface
{
    private const MAX_RESULTS_PER_REQUEST = 10;

    public function __construct(private string $parameter1, private int $parameter2) {}

    public function doFollowUpRequest(string $pageToken): ApiCallerResponse
    {
        $response = doSomeApiCall([
            'pageToken' => $pageToken,
            'parameter1' => $this->parameter1,
            'parameter2' => $this->parameter2
        ]);

        return new ApiCallerResponse($response['items'], $response['nextPageToken']);
    }

    public function doInitialRequest(?int $limit = null, int $offset = 0): ApiCallerResponse
    {
        $limit = (null === $limit || $limit > self::MAX_RESULTS_PER_REQUEST) ? self::MAX_RESULTS_PER_REQUEST : $limit;

        $response = doSomeApiCall([
            'limit' => $limit,
            'offset' => $offset,
            'parameter1' => $this->parameter1,
            'parameter2' => $this->parameter2
        ]);

        return new ApiCallerResponse($response['items'], $response['nextPageToken']);
    }
    
    // ...
}
```

The API caller is used like the one for APIs with limit and offset.
```php
use ITB\ApiCaller\WithPageToken\ApiCallerWrapper;

class SomeService
{
    use ApiCallerWrapper;

    public function someMethode()
    {
        $apiCaller = new AnotherSpecificApiCaller('This is the way!', 1337);
        
        $items = $this->request($apiCaller, null, 10);
        // OR:
        $items = $this->request($apiCaller, 100, 10);
        
        // ...
    }
}
```

## How to test the package?
The package provides PHPUnit tests. In a local environment the tests and static code analysis can be executed via docker.
```bash
./development.sh docker-build
docker-compose run --rm -T phpunit php vendor/bin/phpunit --configuration phpunit.xml tests
docker-compose run --rm -T phpunit php -d memory_limit=2G vendor/bin/phpstan analyse src tests --level 8
```

The PHPUnit tests and a static code analysis via PHPStan are also executed via GitHub actions on any push or PR.
The GitHub Actions CI runs with all supported PHP versions and all supported Symfony versions.

## Contributing
I am really happy that the software developer community loves Open Source, like I do! ♥

That's why I appreciate every issue that is opened (preferably constructive)
and every pull request that provides other or even better code to this package.

You are all breathtaking!