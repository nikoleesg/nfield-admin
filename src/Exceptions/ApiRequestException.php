<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class ApiRequestException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        public readonly ?Response $response = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function body(): ?string
    {
        return $this->response?->body();
    }
}
