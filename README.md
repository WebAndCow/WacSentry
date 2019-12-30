# WacSentry plugin for CakePHP

Plugin CakePHP 3 to connect a CakePHP project to your Sentry account.

You can find IP of client and users.id in the Sentry issue.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is :
```
composer require web-and-cow/wac-sentry
```

Load the plugin :
```
bin/cake plugin load WacSentry
```

And add the configuration to your app.php :
```
'Sentry' => [
    'dsn' => 'https://XXXXXXXXXX@sentry.io/XXXXXX'
]
```

You can send more informations to the issue about the user.
To do this, add this optionnal configuration in the Sentry array in your app.php :
```
'Sentry' => [
    'userFields' => [
        'id',
        'role',
        ...
    ]
]
```
