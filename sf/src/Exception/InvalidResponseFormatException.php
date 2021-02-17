<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvalidResponseFormatException
 * @package App\Exception
 */
class InvalidResponseFormatException extends \Exception
{
    private const MESSAGE = 'Invalid exchange rate API response format.';

    /**
     * InvalidResponseFormatException constructor.
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
