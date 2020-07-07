<?php
namespace WacSentry\Error;
use Exception;

use Cake\Error\ErrorHandler as ErrorHandler;
use Cake\Core\Configure;

class SentryErrorHandler extends ErrorHandler {
    public function handleException(Exception $exception)
    {
        // Si production_only == false ou qu'on n'est pas en debug
        if (Configure::read('Sentry.production_only') === false || !Configure::read('debug')) {
            \Sentry\captureException($exception);
        }

        parent::handleException($exception);
    }
}