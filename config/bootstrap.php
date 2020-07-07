<?php
use Cake\Console\ConsoleErrorHandler;
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
    // Récupération de l'url actuelle
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    // Si il y a d'autres mots interdits configurés dans App.php, on les ajoute
    $unauthorizedWordsInUrl = [];
    if (Configure::read('Sentry.unauthorizedWordsInUrl')) {
        $unauthorizedWordsInUrl = array_merge(Configure::read('Sentry.unauthorizedWordsInUrl'), $unauthorizedWordsInUrl);
    }

    // Expression régulière qui vérifie si un un mot interdit est dans l'url
    $exp = '/' . implode('|', array_map('preg_quote', $unauthorizedWordsInUrl)) . '/i';

    // Si on est en debug true ou qu'il y a un mot interdit, on gère les erreurs normalement
    if (Configure::read('debug') === true || preg_match($exp, $url)) {
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
