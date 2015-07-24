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
'modules' => [
    ...
    'pages' => [
        'class' => 'infoweb\pages\Module',
    ],
],
```

Import the translations and use category 'infoweb/pages':
```
yii i18n/import @infoweb/pages/messages
```

Configuration
-------------
All available configuration options are listed below with their default values.
___
##### enableSliders (type: `boolean`, default: `false`)
If this option is set to `true`, it is possible to attach an entity from the `sliders` module to a page. 
___
##### enablePrivatePages (type: `boolean`, default: `false`)
If this option is set to `true`, the `public` attribute of a page can be managed.
___
##### defaultPublicVisibility (type: `boolean`, default: `true`)
This is the value that will be used as the default value of the `public` attribute of a page.
___
##### ckEditorOptions (type: `array`, default: `['height' => 500]`)
These are the custom options for that will be used for each `ckEditor` instance in this module.
It is also possible to override these settings per instance in its own view.
___