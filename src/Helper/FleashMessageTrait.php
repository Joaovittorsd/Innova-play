<?php

declare(strict_types=1);

namespace Innova\Mvc\Helper;

trait FleashMessageTrait
{
    private function addErrorMessage(string $errorMessage): void 
    {
        $_SESSION['error_message'] = $errorMessage;
    }
}