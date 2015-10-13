Pages extension for Yii 2
================================
With this extension you can manage pages that can be used in a website.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist infoweb-internet-solutions/yii2-cms-pages "*"
```

or add

```
"infoweb-internet-solutions/yii2-cms-pages": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed run this migration

```
yii migrate/up --migrationPath=@infoweb/pages/migrations
```

Enable the module in `backend/config/main.php`:

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
And finally enable the page component in `frontend/config/main.php`:
```php
'components' => [
	...
    'page' => [
    	'class' => 'infoweb\pages\components\Page'
    ]
]
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
##### allowContentDuplication (type: `boolean`, default: `true`)
If this option is set to `true`, the `duplicateable` jquery plugin is activated on all translateable attributes.
___
##### ckEditorOptions (type: `array`, default: `['height' => 500]`)
These are the custom options for that will be used for each `ckEditor` instance in this module.
It is also possible to override these settings per instance in its own view.
___