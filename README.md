# WacSentry plugin for CakePHP

Plugin CakePHP 3 to connect a CakePHP project to your Sentry account.

You can find IP of client and users.id in the Sentry issue.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is :
**CakePHP 3**
```
composer require web-and-cow/wac-sentry:^1.0.0
```

**CakePHP 4**
```
composer require web-and-cow/wac-sentry:^2.0.0
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

You can also block event when a word is in the URL to avoid spam of bots. 
To do this, add this optionnal configuration in the Sentry array in your app.php :
```
'Sentry' => [
    'unauthorizedWordsInUrl' => [
        'robots.txt',
        'magento',
        ...
    ]
]
```