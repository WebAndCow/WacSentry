<?php
namespace WacSentry\Error\Middleware;

use \Cake\Error\Middleware\ErrorHandlerMiddleware as ErrorHandlerMiddleware;
use Cake\Core\Configure;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Cake\Core\Exception\MissingPluginException;
use Cake\Http\Exception\MissingControllerException;
use Throwable;

class SentryErrorHandlerMiddleware extends ErrorHandlerMiddleware {
    
    public function handleException(Throwable $exception, ServerRequestInterface $request): ResponseInterface
    {
        // Si avoid_bot_scan_errors est à false ou que ce n'est pas une erreur MissingController ou MissingPlugin
        if (Configure::read('Sentry.avoid_bot_scan_errors') === false || !($exception instanceof MissingControllerException || $exception instanceof MissingPluginException)) {
            // Récupération de l'IP de l'user
            $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

            \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($ip, $request): void {
                // Ajout de l'adresse IP dans l'issue
                $scope->setExtra('IP', $ip);
                
                // On récupère les champs de l'utilisateur qu'on souhaite envoyer dans l'issue
                if (!empty(Configure::read('Sentry.userFields'))) {
                    foreach (Configure::read('Sentry.userFields') as $userField) {
                        $userFields[$userField] = $request->getSession()->read('Auth.User.' . $userField);
                    }
                }

                // Si on a des champs d'utilisateur à envoyer, on les ajoutes dans l'issue
                if (isset($userFields) && !empty($userFields)) {
                    $scope->setUser($userFields);
                }
            });

            \Sentry\captureException($exception);
        }

        return parent::handleException($exception, $request);
    }
}
