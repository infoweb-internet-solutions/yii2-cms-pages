Simple pages extension for Yii 2
================================
This extension provides pages that can be added to a menu

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist infoweb/yii2-pages "*"
```

or add

```
"infoweb/yii2-pages": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed run this migration

```
yii migrate/up --migrationPath=@infoweb/pages/migrations
```

and add this to your config

````
'pages' => [
    'class' => 'infoweb\pages\Module',
],
````


TBD
---

Update readme

Add I18N, translations and translations with tabs

add this to config:
    'modules' => [
    'gridview' => [
    'class' => '\kartik\grid\Module'
    ]
    ];

