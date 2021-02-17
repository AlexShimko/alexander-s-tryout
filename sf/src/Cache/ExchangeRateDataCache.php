<?php

declare(strict_types=1);

namespace App\Cache;

/**
 * Class ExchangeRateDataCache
 * Purpose is storing exchange rates in memory to reduce amount of API requests.
 * If huge amount of exchanges will be required - this will help.
 * @package App\Cache
 */
class ExchangeRateDataCache extends AbstractDataCache { }
