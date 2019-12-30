<?php
namespace WacSentry\Error;
use Throwable;

use Cake\Error\ErrorHandler as ErrorHandler;

class SentryErrorHandler extends ErrorHandler {
    public function handleException(Throwable $exception): void
    {
        \Sentry\captureException($exception);

        parent::handleException($exception);
    }
}