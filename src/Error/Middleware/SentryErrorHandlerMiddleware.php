<?php
namespace WacSentry\Error\Middleware;

use \Cake\Error\Middleware\ErrorHandlerMiddleware as ErrorHandlerMiddleware;

class SentryErrorHandlerMiddleware extends ErrorHandlerMiddleware {
    public function handleException($exception, $request, $response)
    {
        // IP de l'user
        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

        \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($ip, $request): void {
            $scope->setExtra('IP', $ip);
            $scope->setUser(['ID' => $request->session()->read('Auth.User.id')]);
            $scope->setUser(['role' => $request->session()->read('Auth.User.role')]);
        });

        \Sentry\captureException($exception);

        return parent::handleException($exception, $request, $response);
    }
}
