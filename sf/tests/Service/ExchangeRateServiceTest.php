<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Enum\CurrencyEnum;
use App\Exception\BaseCurrencyNotSpecifiedException;
use App\Service\ExchangeRateService;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ExchangeRateServiceTest
 * @package App\Tests\Service
 */
class ExchangeRateServiceTest extends TestCase
{
    /**
     * @var ExchangeRateService
     */
    private ExchangeRateService $class;

    /**
     * @var string
     */
    private string $defaultCurrency;

    /**
     * @var DateTime
     */
    private DateTime $todayDate;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->class = new ExchangeRateService(new CurlHttpClient(), 'https://api.exchangeratesapi.io/');
        $this->defaultCurrency = CurrencyEnum::getDefaultCurrency();
        $this->todayDate = new DateTime();
    }

    /**
     * @throws BaseCurrencyNotSpecifiedException
     * @throws BadRequestHttpException
     */
    public function testTodayExchangeRates()
    {
        $apiResponse = $this->class->getExchangeRateForCurrency($this->defaultCurrency, $this->todayDate);

        $this->assertArrayHasKey('USD', $apiResponse);
        $this->assertArrayHasKey('JPY', $apiResponse);

        $this->assertIsFloat($apiResponse['USD']);
        $this->assertIsFloat($apiResponse['JPY']);
    }

    /**
     * @throws BaseCurrencyNotSpecifiedException
     * @throws BadRequestHttpException
     */
    public function testPastExchangeRates()
    {
        $date = DateTime::createFromFormat('Y-m-d', '2016-02-19');
        $apiResponse = $this->class->getExchangeRateForCurrency($this->defaultCurrency, $date);

        $this->assertArrayHasKey('USD', $apiResponse);
        $this->assertArrayHasKey('JPY', $apiResponse);

        $this->assertSame(1.1096, $apiResponse['USD']);
        $this->assertSame(125.4, $apiResponse['JPY']);
    }

    /**
     * @throws BaseCurrencyNotSpecifiedException
     * @throws BadRequestHttpException
     */
    public function testBadRequestException()
    {
        $this->expectException(BadRequestHttpException::class);
        $this->class->getExchangeRateForCurrency('UNK', $this->todayDate);
    }

    /**
     * @throws BaseCurrencyNotSpecifiedException
     * @throws BadRequestHttpException
     */
    public function testException()
    {
        $this->expectException(BaseCurrencyNotSpecifiedException::class);
        $this->class->getExchangeRateForCurrency('', $this->todayDate);
    }
}
