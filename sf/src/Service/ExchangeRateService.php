<?php

declare(strict_types=1);

namespace App\Service;

use App\Cache\ExchangeRateDataCache;
use App\Exception\BaseCurrencyNotSpecifiedException;
use App\Exception\InvalidResponseFormatException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ExchangeRateService implement API request to get exchange rates for currency
 * @package App\Service
 */
class ExchangeRateService
{
    /**
     * @var ExchangeRateDataCache
     */
    private ExchangeRateDataCache $cache;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    /**
     * @var string
     */
    private string $exchangeApiUrl;

    /**
     * ExchangeRateService constructor.
     * @param HttpClientInterface $client
     * @param string $exchangeApiUrl
     */
    public function __construct(HttpClientInterface $client, string $exchangeApiUrl)
    {
        $this->client = $client;
        $this->exchangeApiUrl = $exchangeApiUrl;
        $this->cache = ExchangeRateDataCache::getInstance();
    }

    /**
     * @param string $currency
     * @param \DateTimeInterface|null $date
     * @return array
     * @throws BaseCurrencyNotSpecifiedException
     */
    public function getExchangeRateForCurrency(string $currency, ?\DateTimeInterface $date): array
    {
        if (!$currency) {
            throw new BaseCurrencyNotSpecifiedException();
        }

        if ($rates = $this->cache->getCache($this->generateCacheKey($currency, $date))) {
            return $rates;
        }

        return $this->fetchExchangeRates($currency, $date);
    }

    /**
     * @param string $currency
     * @param \DateTimeInterface $date
     * @return array
     * @throws BadRequestHttpException
     */
    protected function fetchExchangeRates(string $currency, \DateTimeInterface $date): array
    {
        $path = $date === null ? (new \DateTime())->format('Y-m-d') : $date->format('Y-m-d');
        try {
            $response = $this->client->request(
                'GET',
                $this->exchangeApiUrl . $path,
                ['query' => ['base' => $currency]]
            );

            $data = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

            if (!isset($data['rates'])) {
                throw new InvalidResponseFormatException();
            }
        } catch (\Throwable $exception) {
            // Simplifying potential exception stack to one simple exception
            throw new BadRequestHttpException($exception->getMessage());
        }

        $returnData = $data['rates'];
        $this->cache->setCache($this->generateCacheKey($currency, $date), $returnData);

        return $returnData;
    }

    /**
     * @param string $currency
     * @param \DateTimeInterface|null $date
     * @return string
     */
    private function generateCacheKey(string $currency, ?\DateTimeInterface $date): string
    {
        $dateKey = $date === null
            ? (new \DateTime())->format('Y-m-d')
            : $date->format('Y-m-d');

        return $currency . $dateKey;
    }
}
