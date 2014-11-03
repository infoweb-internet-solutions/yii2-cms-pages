Do not use this module! It's far from ready and it\'s not yet usefull

Simple pages extension for Yii 2
================================
This extension provides pages that can be added to a menu

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist infoweb-internet-solutions/yii2-pages "*"
```

or add

```
"infoweb-internet-solutions/yii2-pages": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed run this migration

```
yii migrate/up --migrationPath=@infoweb/pages/migrations
```

And modify your backend configuration as follows:

```php
return [
    ...
    'modules' => [
        'pages' => [
            'class' => 'infoweb\pages\Module',
        ],
    ],
    ...
];
```

Import the translations and use category 'infoweb/pages':
```
yii i18n/import @infoweb/pages/messages
```
