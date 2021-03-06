# WacSentry plugin for CakePHP

Plugin CakePHP to connect a CakePHP project to your Sentry account.

You can find IP of client and users.id in the Sentry issue.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is :

**CakePHP 3**
```
composer require web-and-cow/wac-sentry:"^1.0.0"
```

**CakePHP 4**
```
composer require web-and-cow/wac-sentry:"^2.0.0"
```

Load the plugin :

**CakePHP 3**
```
bin/cake plugin load WacSentry -b
```

**CakePHP 4**
```
bin/cake plugin load WacSentry
```

And add the configuration to your app.php :
```
'Sentry' => [
    'dsn' => 'https://XXXXXXXXXX@sentry.io/XXXXXX', // The DSN PHP Key of Sentry
    'avoid_bot_scan_errors' => true, // Default true : if true, MissingControllerException and MissingPluginException aren't sent to Sentry to avoid bot scan errors
    'userFields' => [ // List of user session values sent to Sentry
        'id',
        'role',
        ...
    ],
    'unauthorizedWordsInUrl' => [ // If a word of this array is in the url, the error event will not be sent to Sentry
        'robots.txt',
        'wp-admin',
        ...
    ]
]
```
