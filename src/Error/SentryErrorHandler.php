<?php
namespace WacSentry\Error;
use Exception;

use Cake\Error\ErrorHandler as ErrorHandler;

class SentryErrorHandler extends ErrorHandler {
    public function handleException(Exception $exception)
    {
        \Sentry\captureException($exception);

        parent::handleException($exception);
    }
}