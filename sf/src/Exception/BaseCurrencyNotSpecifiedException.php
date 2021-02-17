<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseCurrencyNotSpecifiedException
 * @package App\Exception
 */
class BaseCurrencyNotSpecifiedException extends \Exception
{
    private const MESSAGE = 'Base currency is not specified.';

    /**
     * BaseCurrencyNotSpecifiedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = self::MESSAGE,
        $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
