<?php

namespace App\Exceptions;

use App\Traits\HttpResponse;
use Exception;

class AccessDeniedException extends Exception
{
    use HttpResponse;
    /**
     * Construtor da exceção.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct($message = "Acesso negado")
    {
        parent::__construct($message);
    }

    /**
     * Render a response for the exception.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return $this->authResourceDenied($this->getMessage());
    }
}
