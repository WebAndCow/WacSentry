<?php
use Cake\Core\Configure;
use Psr\Http\Message\ServerRequestInterface;
use Cake\Http\ServerRequestFactory;
use Cake\Error\ErrorHandler;
use WacSentry\Error\SentryErrorHandler;
use WacSentry\Error\Middleware\SentryErrorHandlerMiddleware;

$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    // Si on est en debug true, on gère les erreurs normalement
    if (Configure::read('debug') === true) {
        (new ErrorHandler(Configure::read('Error')))->register();

    // Sinon, on utilise Sentry
    } else {
        \Sentry\init(['dsn' => Configure::read('Sentry.dsn')]);

        (new SentryErrorHandler(Configure::read('Error')))->register();

        $appClass = Configure::read('App.namespace') . '\Application';
        if (class_exists($appClass)) {
            \Cake\Event\EventManager::instance()->on('Server.buildMiddleware', function ($event, $queue) {
                /* @var \Cake\Http\MiddlewareQueue $queue */
                $middleware = new SentryErrorHandlerMiddleware();
                try {
                    $queue->insertAfter(\Cake\Error\Middleware\ErrorHandlerMiddleware::class, $middleware);
                } catch (LogicException $e) {
                    $queue->prepend($middleware);
                }
            });
        }
    }
}