<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NotImplementedStrategyException
 * @package App\Exception
 */
class NotImplementedStrategyException extends \Exception
{
    private const MESSAGE = 'Strategy is not implemented.';

    /**
     * NotImplementedStrategyException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = self::MESSAGE,
        $code = Response::HTTP_NOT_IMPLEMENTED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
