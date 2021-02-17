<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvalidInputDataFormatException
 * @package App\Exception
 */
class InvalidInputDataFormatException extends \Exception
{
    private const MESSAGE = 'Invalid input data format.';

    /**
     * InvalidInputDataFormatException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = self::MESSAGE,
        $code = Response::HTTP_BAD_REQUEST,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
